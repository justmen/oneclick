document.addEventListener('DOMContentLoaded', function() {

    var modalButtons = document.querySelectorAll('.ligacom-js-open-modal')
    var overlay      = document.querySelector('.ligacom-js-overlay-modal')
    var closeButtons = document.querySelectorAll('.ligacom-js-modal-close')
    var buyoneclickBtn = document.querySelectorAll('.ligacom_oneclick_btn')

    buyoneclickBtn.forEach(function (item) {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            var oneclickForm = document.querySelector('#ligacom_oneclick_form')
            var iblock_id = item.dataset.ligacom_oneclick_iblock_id
            var product_id = item.dataset.ligacom_oneclick_product_id
            var product_name = item.dataset.ligacom_oneclick_product_name
            var price_code = item.dataset.ligacom_oneclick_price_code
            var currency = item.dataset.ligacom_oneclick_currency

            document.querySelector('[name="ligacom_oneclick_form_iblock_id"]').value = iblock_id
            document.querySelector('[name="ligacom_oneclick_form_product_id"]').value = product_id
            document.querySelector('[name="ligacom_oneclick_form_product_name"]').value = product_name
            document.querySelector('[name="ligacom_oneclick_form_price_code"]').value = price_code
            document.querySelector('[name="ligacom_oneclick_form_currency"]').value = currency
            document.querySelector('.ligacom-modal__title').textContent = product_name
            document.querySelector('[name="ligacom_oneclick_form_image"]').alt = product_name
        })
    })

    modalButtons.forEach(function(item){
        item.addEventListener('click', function(e) {
            e.preventDefault()
            var modalId = this.dataset.ligacom_modal
            var modalElem = document.querySelector('.ligacom-modal[data-ligacom_modal="' + modalId + '"]')
            modalElem.classList.add('active');
            overlay.classList.add('active');
        });
    });

    closeButtons.forEach(function(item){
        item.addEventListener('click', function(e) {
            var parentModal = this.closest('.ligacom-modal');
            parentModal.classList.remove('active');
            overlay.classList.remove('active');
        });
    });

    document.body.addEventListener('keyup', function (e) {
        var key = e.keyCode;
        if (key == 27) {
            document.querySelector('.ligacom-modal.active').classList.remove('active')
            document.querySelector('.ligacom-overlay').classList.remove('active')
        }
    }, false);

    overlay.addEventListener('click', function() {
        document.querySelector('.ligacom-modal.active').classList.remove('active')
        this.classList.remove('active')
    });
});