(function () {
    'use strict';

    var modal = document.getElementById('modal');
    var aside = document.querySelector('.container aside');
    var registerBox = document.getElementById('register-box');

    window.showModal = function (type) {
        if (!modal) {
            window.location.href = type === 2 ? 'anmelden.php' : 'login.php';
            return;
        }
        var title = modal.querySelector('.modal-title');
        var loginList = modal.querySelector('.server-item1');
        var regList = modal.querySelector('.server-item2');
        if (title) {
            title.textContent = type === 2
                ? 'أختر عالماً للتسجيل'
                : 'أختر عالماً لتسجيل الدخول';
        }
        if (loginList) {
            loginList.style.display = type === 1 ? 'block' : 'none';
        }
        if (regList) {
            regList.style.display = type === 2 ? 'block' : 'none';
        }
        modal.style.display = 'block';
        document.body.classList.add('blur');
    };

    window.closeModal = function () {
        if (modal) {
            modal.style.display = 'none';
        }
        document.body.classList.remove('blur');
    };

    window.showSide = function () {
        if (aside) {
            aside.style.right = '0';
        }
    };

    window.closeSide = function () {
        if (aside) {
            aside.style.right = '-100%';
        }
    };

    document.addEventListener('click', function (ev) {
        if (ev.target && ev.target.classList && ev.target.classList.contains('musk')) {
            closeModal();
        }
    });

    if (registerBox) {
        registerBox.classList.add('box-show');
    }
})();
