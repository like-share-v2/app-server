<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>注册</title>
    <link rel="stylesheet" href="/css/login.css">
    <script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <div class="logo">
{{--        <span class="logo_text"><img src="/images/logo.png" width=71px height=71px></span>--}}
    </div>
    <div class="phoneNumber">
        <img src="/images/icon_avatar.png" style="width: 16px;height: 22px;">
        <input type="text" placeholder="输入账号" class="phoneInput" id="account" />
    </div>
    <div class="phoneNumber" style="margin-top: 20px;">
        <img src="/images/icon_mima.png" style="width: 16px;height: 18px;">
        <input type="password" placeholder="输入密码" class="phoneInput" id="password" />
    </div>
    <div class="phoneNumber" style="margin-top: 20px;">
        <img src="/images/icon_mima.png" style="width: 16px;height: 18px;">
        <input type="password" placeholder="确认密码" class="phoneInput" id="password_confirmation" />
    </div>
    <div class="phoneNumber">
        <img src="/images/icon_iphone.png" style="width: 16px;height: 22px;">
        <input type="text" oninput = "value=value.replace(/[^\d]/g,'')" maxlength="11" placeholder="输入手机号" class="phoneInput" id="phone" />
    </div>
    <div class="phoneNumber" style="margin-top: 20px;">
        <img src="/images/icon_shuru.png" style="width: 16px;height: 18px;">
        <input type="text" oninput = "value=value.replace(/[^\d]/g,'')" maxlength="6" placeholder="输入验证码" class="phoneInput" id="code"/>
        <span class="getcode">获取验证码</span>
    </div>
    <div class="phoneNumber" style="margin-top: 20px;">
        <img src="/images/icon_shuru.png" style="width: 16px;height: 18px;">
        <input type="text" placeholder="请输入邀请码" class="phoneInput" id="invitation_code" />
    </div>
    <button type="button" class="btn reg">立即注册</button>
</div>
</body>
</html>
<script src="/layer_mobile/layer.js"></script>
<script>
    let cd = 60;
    (function ($) {
        $.getUrlParam = function (name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
    })(jQuery);
    var invitation_code = $.getUrlParam('invitation_code');
    window.onload = function(){
        $("#invitation_code").val(invitation_code);
    };
    $(document).ready(() => {
        $('.reg').click(() => {
            const account = $('#account').val();
            const phone = $('#phone').val();
            const password = $('#password').val();
            const password_confirmation = $('#password_confirmation').val();
            const code = $('#code').val();
            const invitation_code = $('#invitation_code').val();

            if (account === '') {
                return layer.open({ content: '请输入账号', skin: 'msg', time: 2 });
            }
            if (phone === '') {
                return layer.open({ content: '请输入手机号码', skin: 'msg', time: 2 });
            }
            if (password === '') {
                return layer.open({ content: '请输入登录密码', skin: 'msg', time: 2 });
            }
            if (password_confirmation === '') {
                return layer.open({ content: '请输入确认密码', skin: 'msg', time: 2 });
            }
            if (code === '') {
                return layer.open({ content: '请输入验证码', skin: 'msg', time: 2 });
            }
            if (invitation_code === '') {
                return layer.open({ content: '请输入邀请码', skin: 'msg', time: 2 });
            }
            layer.open({ type: 2 });
            $.ajax({
                type: 'POST',
                url: '/v1/auth/html_register',
                data: {
                    account: account,
                    password: password,
                    password_confirmation: password_confirmation,
                    phone: phone,
                    code: code,
                    invitation_code: invitation_code
                },
                success: (res) => {

                    layer.closeAll();
                    layer.open({ content: '注册成功', skin: 'msg', time: 2 });
                    setTimeout(() => {
                        location.href = res.result.url
                    }, 2000);
                    if (res.code !== 200) return layer.open({ content: res.message, skin: 'msg', time: 2 });
                    layer.open({ content: '注册成功', skin: 'msg', time: 2 });
                    setTimeout(() => {
                        location.href = res.result.url
                    }, 1000)
                },
                dataType: 'json'
            });
        });
        $('.getcode').click(() => {
            if (cd !== 60) return;
            const phone = $('#phone').val();
            if (phone === '') {
                return layer.open({ content: '请输入手机号码', skin: 'msg', time: 2 });
            }
            if (phone.length !== 11) {
                return layer.open({ content: '手机号码格式不正确', skin: 'msg', time: 2 });
            }
            layer.open({ type: 2 });
            $.ajax({
                type: 'GET',
                url: '/v1/sms/sendCode/' + phone,
                data: { type: 3 },
                success: (res) => {
                    layer.closeAll();
                    if (res.code !== 200) return layer.open({ content: res.message, skin: 'msg', time: 2 });

                    let id = setInterval(() => {
                        if (cd < 0) {
                            clearInterval(id)
                            $('.getcode').text('获取验证码')
                            cd = 120
                        } else {
                            $('.getcode').text('请稍后(' + cd + 's)')
                            cd--;
                        }
                    }, 1000)

                    return layer.open({ content: '获取验证码成功', skin: 'msg', time: 2 });
                },
                dataType: 'json'
            });
        })
    })
</script>
