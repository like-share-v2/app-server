<?php

declare(strict_types=1);
/**
 * @copyright 
 * @version 1.0.0
 * @link  
 */

namespace App\Kernel\Utils;

use App\Constants\Constants;
use App\Exception\ResponseException;
use App\Model\User;

use App\Service\Dao\UserDAO;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Hyperf\Utils\Context;
use Hyperf\Utils\Traits\StaticInstance;

/**
 * JWT服务
 *
 *
 * @package App\Kernel\Utils
 */
class JwtInstance
{
    use StaticInstance;

    /**
     * @string
     */
    CONST KEY = 'mBC5v1sOKVvbdEitdSBenu59nfNfhwkedkJVNabosTw=';

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var User
     */
    public $user;

    /**
     * 构建token
     *
     * @param $userModel
     * @return string
     */
    public function encode(User $userModel): string
    {
        $this->user_id = (int)$userModel->id;
        $this->user = $userModel;

        return JWT::encode([
            'iss' => '',
            'iat' => time(),
            'exp' => time() + Constants::AUTHORIZATION_EXPIRE,
            'id' => $userModel->id
        ], self::KEY);
    }

    /**
     * 解析token
     *
     * @param string $token
     * @return $this
     */
    public function decode(string $token): self
    {
        try {
            $decode = (array)JWT::decode($token, self::KEY, ['HS256']);
        }
        // token过期
        catch (ExpiredException $e) {
            throw new ResponseException('logic.LOGIN_EXPIRED', 401);
        }
        // 其他异常
        catch (\Throwable $e) {
            throw new ResponseException('logic.SERVER_ERROR');
        }

        if ($id = $decode['id'] ?? null) {
            $this->user_id = $id;
            $this->user = di(UserDAO::class)->getUserById((int)$id);

            // token进入续期时间段, 生成新的token并放到header里
            if ($decode['exp'] - Constants::AUTHORIZATION_RENEW <= time()) {
                Context::set('ExchangeToken', $this->encode($this->user));
            }
        }

        return $this;
    }

    public function build(): self
    {
        if (empty($this->user_id)) {
            throw new ResponseException('app.NEED_LOGIN', 401);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->user_id;
    }

    public function getUser(): ?User
    {
        if ($this->user === null && $this->user_id) {
            $this->user = di(UserDAO::class)->getUserById($this->user_id);
        }

        return $this->user;
    }
}