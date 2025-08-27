import $ from 'jquery';
import * as bootstrap from "bootstrap";
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import toastr from 'toastr';

DataTable.use(bootstrap);

export default class MasterDataHandler {
    constructor(config) {
        this.config = config;
        this.dataTable = null;
        this.tableElement = $(this.config.tableId);

        if (!this.config.tableId || !this.config.entity || !this.config.columns) {
            console.error("Konfigurasi MasterDataHandler tidak lengkap!");
            return;
        }

        this.initDataTables();
        this.initEventListeners();
    }

    buildUrl(action) {
        const basePath = this.config.basePath || 'master_data';
        return `${window.location.origin}/pkl/${basePath}/${this.config.entity}/${action}`;
    }

    initDataTables() {
        this.dataTable = new DataTable(this.config.tableId, {
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: { url: this.buildUrl('get_data'), type: "POST" },
            columns: [
                ...this.config.columns,
                {
                    data: null, orderable: false, searchable: false, className: 'text-center',
                    render: (data, type, row) => this.createActionButtons(row)
                }
            ],
        });
    }

    createActionButtons(data) {
        return `
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary js-view-item" data-id="${data.id}">view</button>
                <button type="button" class="btn btn-sm btn-outline-primary js-edit-item" data-id="${data.id}">edit</button>
                <button type="button" class="btn btn-sm btn-outline-primary js-delete-item" data-id="${data.id}">delete</button>
            </div>
        `;
    }

    initEventListeners() {
        this.tableElement.on('click', '.js-view-item', e => this.viewItem($(e.currentTarget).data('id')));
        this.tableElement.on('click', '.js-edit-item', e => this.editItem($(e.currentTarget).data('id')));
        this.tableElement.on('click', '.js-delete-item', e => this.deleteItem($(e.currentTarget).data('id')));

        const createButton = document.getElementById(this.config.createButtonId || 'createButton');
        if (createButton) {
            createButton.addEventListener('click', () => this.createItem());
        }
    }

    showModal(action, modalId, contentId, data = null) {
        $.ajax({
            url: this.buildUrl(action), type: "POST", data: data, dataType: "json",
            success: (response) => {
                $(contentId).html(response);
                const modal = new bootstrap.Modal(document.getElementById(modalId));
                modal.show();

                if (this.config.onModalShow) {
                    this.config.onModalShow(action, modal);
                }

                const form = $(contentId).find('form').get(0);
                if (form) this.handleFormSubmit(form, modal);
            },
            error: () => toastr.error(`Gagal memuat form ${action}.`),
        });
    }

    handleFormSubmit(formElement, modalInstance) {
        formElement.addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                const response = await fetch(formElement.action, {
                    method: 'POST', body: new FormData(formElement), headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                if (result.status === 'success') {
                    toastr.success('Data berhasil diproses.');
                } else {
                    toastr.error(result.message || 'Data gagal diproses.');
                }
            } catch (error) {
                toastr.error('Terjadi kesalahan saat mengirim data.');
            } finally {
                modalInstance.hide();
                this.dataTable.ajax.reload();
            }
        });
    }

    createItem() { this.showModal('create', 'createModal', '#createModalContent'); }
    viewItem(id) { this.showModal('show', 'showModal', '#showModalContent', { id }); }
    editItem(id) { this.showModal('edit', 'editModal', '#editModalContent', { id }); }

    deleteItem(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            $.ajax({
                url: this.buildUrl('delete'), type: "POST", data: { id }, dataType: "json",
                success: (response) => {
                    if (response.status === 'success') {
                        toastr.success('Data berhasil dihapus');
                        this.dataTable.ajax.reload();
                    } else {
                        toastr.error(response.message || 'Data gagal dihapus');
                    }
                },
                error: () => toastr.error('Terjadi kesalahan saat menghapus data'),
            });
        }
    }
}