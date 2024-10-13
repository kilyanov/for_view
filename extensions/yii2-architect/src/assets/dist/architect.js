$(document).ready(function () {

    let modal;
    if ($('#js-ajax-modal').length > 0 && $('#js-ajax-modal').length === 0) {
        modal = new ModalRemote('#js-ajax-modal');
    } else {
        modal = new ModalRemote('#js-ajax-modal');
    }

    $(document).on('click', '[role="modal-remote"]', function (event) {
        event.preventDefault();
        modal.open(this, null);
    });

    $(document).on('click', '[role="modal-remote-bulk"]', function (event) {
        event.preventDefault();

        let selectedIds = [];
        $('input:checkbox[name="selection[]"]').each(function () {
            if (this.checked)
                selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            modal.show();
            modal.setTitle('Ничего не выбрано');
            modal.setContent('Вы не выбрали не одного элемента');
            modal.addFooterButton(
                'Закрыть',
                'button',
                'btn btn-secondary',
                function (button, event) {
                    this.hide();
                }
            );
        } else {
            modal.open(this, selectedIds);
        }
    });
});
