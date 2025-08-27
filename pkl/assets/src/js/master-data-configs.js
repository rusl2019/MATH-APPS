import $ from 'jquery';
import select2 from 'select2';

select2($);

const configs = {
    student: {
        tableId: '#master-data-student',
        entity: 'student',
        basePath: 'master_data',
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "email" },
            { data: "study_program" },
        ],
        onModalShow: function (action, modal) {
            const modalElement = modal._element;
            const selectOptions = { theme: 'bootstrap-5', dropdownParent: $(modalElement) };
            if (action === 'create') $("#roles_create").select2(selectOptions);
            if (action === 'edit') $("#roles_edit").select2(selectOptions);
            if (action === 'show') $("#roles_detail").select2(selectOptions);
        }
    },
    lecturer: {
        tableId: '#master-data-lecturer',
        entity: 'lecturer',
        basePath: 'master_data',
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "email" },
            { data: "rank" },
            { data: "position" },
        ],
        onModalShow: function (action, modal) {
            const modalElement = modal._element;
            const selectOptions = { theme: 'bootstrap-5', dropdownParent: $(modalElement) };
            if (action === 'create') $("#roles_create").select2(selectOptions);
            if (action === 'edit') $("#roles_edit").select2(selectOptions);
            if (action === 'show') $("#roles_detail").select2(selectOptions);
        }
    },
    staff: {
        tableId: '#master-data-staff',
        entity: 'staff',
        basePath: 'master_data',
        columns: [
            { data: "id" },
            { data: "name" },
            { data: "email" },
        ],
        onModalShow: function (action, modal) {
            const modalElement = modal._element;
            const selectOptions = { theme: 'bootstrap-5', dropdownParent: $(modalElement) };
            if (action === 'create') $("#roles_create").select2(selectOptions);
            if (action === 'edit') $("#roles_edit").select2(selectOptions);
            if (action === 'show') $("#roles_detail").select2(selectOptions);
        }
    },
    study_program: {
        tableId: '#master-data-study_program',
        entity: 'study_program',
        basePath: 'master_data',
        columns: [
            { data: "name" },
            { data: "lecturer_name" },
        ],
        onModalShow: function (action, modal) {
            const modalElement = modal._element;
            const selectOptions = { theme: 'bootstrap-5', dropdownParent: $(modalElement) };
            if (action === 'create') $("#lecturer_id_create").select2(selectOptions);
            if (action === 'edit') $("#lecturer_id_edit").select2(selectOptions);
            if (action === 'show') $("#lecturer_id_detail").select2(selectOptions);
        }
    },
    module: {
        tableId: '#master-data-module',
        entity: 'module',
        basePath: 'master_data',
        columns: [
            { data: "name" },
            { data: "description" },
        ]
    },
    role: {
        tableId: '#master-data-role',
        entity: 'role',
        basePath: 'master_data',
        columns: [
            { data: "name" },
            { data: "description" },
        ]
    },
};

export default configs;