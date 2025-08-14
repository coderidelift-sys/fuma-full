document.addEventListener("DOMContentLoaded", function () {
    // Utility function to update the state of the "Select All" checkbox
    function updateSelectAllCheckbox() {
        const checkboxes = document.querySelectorAll(
            ".table .form-check-input:not(#selectAll)"
        );
        const selectAllCheckbox = document.getElementById("selectAll");
        const allChecked = Array.from(checkboxes).every(
            (checkbox) => checkbox.checked
        );
        const anyChecked = Array.from(checkboxes).some(
            (checkbox) => checkbox.checked
        );

        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
    }

    // Handle "Select All" checkbox state changes
    document
        .getElementById("selectAll")
        .addEventListener("change", function () {
            const isChecked = this.checked;
            document
                .querySelectorAll(".table .form-check-input:not(#selectAll)")
                .forEach((checkbox) => {
                    checkbox.checked = isChecked;
                });
            updateSelectAllCheckbox();
        });

    // Handle individual permission checkbox changes
    document
        .querySelectorAll(".table .form-check-input:not(#selectAll)")
        .forEach((checkbox) => {
            checkbox.addEventListener("change", updateSelectAllCheckbox);
        });

    // Handle accordion header checkbox changes
    document.querySelectorAll(".accordion-button").forEach((button) => {
        const headerCheckbox = button.querySelector("input.form-check-input");
        const accordionId = button.getAttribute("data-bs-target").substring(1);

        function toggleCheckboxes(isChecked) {
            document
                .querySelectorAll(`#${accordionId} .form-check-input`)
                .forEach((checkbox) => {
                    checkbox.checked = isChecked;
                });
            updateSelectAllCheckbox();
        }

        headerCheckbox.addEventListener("change", function () {
            toggleCheckboxes(this.checked);
        });

        button.addEventListener("click", function () {
            toggleCheckboxes(headerCheckbox.checked);
        });
    });

    updateSelectAllCheckbox();

    // Form validation setup
    (function () {
        const validateForm = (formSelector, fieldsConfig) => {
            const formElement = document.querySelector(formSelector);
            if (!formElement) return;

            FormValidation.formValidation(formElement, {
                fields: fieldsConfig,
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap5: new FormValidation.plugins.Bootstrap5({
                        eleValidClass: "",
                        rowSelector: ".col-12",
                    }),
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    autoFocus: new FormValidation.plugins.AutoFocus(),
                },
                init: (instance) => {
                    instance.on("plugins.message.placed", (e) => {
                        if (
                            e.element.parentElement.classList.contains(
                                "input-group"
                            )
                        ) {
                            e.element.parentElement.insertAdjacentElement(
                                "afterend",
                                e.messageElement
                            );
                        }
                    });

                    instance.on("core.element.validated", (e) => {
                        if (e.valid) {
                            e.element.classList.add("is-valid");
                        }
                    });

                    instance.on("core.form.valid", () => formElement.submit());
                },
            });
        };

        validateForm("#addRoleForm", {
            name: {
                validators: {
                    notEmpty: {
                        message: "Please enter a role name",
                    },
                    stringLength: {
                        min: 3,
                        message: "Role name must be at least 3 characters long",
                    },
                },
            },
            "permissions[]": {
                validators: {
                    choice: {
                        min: 1,
                        message: "Please select at least one permission",
                    },
                },
            },
        });
    })();

    // Handle role deletion
    document.addEventListener("click", function (e) {
        const target = e.target.closest(".role-delete-modal");
        if (target) {
            e.preventDefault();

            const roleId = target.getAttribute("data-id");
            if (!roleId) {
                console.error("Role ID not found.");
                return;
            }

            const form = document.createElement("form");
            form.method = "POST";
            form.action = urlDeleteRole.replace(":id", roleId);
            form.style.display = "none";

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement("input");
                csrfInput.type = "hidden";
                csrfInput.name = "_token";
                csrfInput.value = csrfToken.getAttribute("content");
                form.appendChild(csrfInput);
            }

            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "DELETE";
            form.appendChild(methodInput);

            document.body.appendChild(form);

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else {
                    form.remove();
                }
            });
        }
    });

    // Handle add new role
    document
        .getElementById("addNewRole")
        .addEventListener("click", function () {
            const modalTitle = document.querySelector(
                "#addRoleModal .role-title"
            );
            modalTitle.textContent = "Add New Role";

            const roleForm = document.getElementById("addRoleForm");

            const methodInput = roleForm.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();

            roleForm.reset();
            roleForm
                .querySelectorAll("input[type=checkbox]")
                .forEach((checkbox) => {
                    checkbox.checked = false;
                });

            const addRoleModal = new bootstrap.Modal(
                document.getElementById("addRoleModal")
            );
            addRoleModal.show();
        });

    // Handle edit role
    document.addEventListener("click", function (e) {
        const target = e.target.closest(".role-edit-modal");
        if (target) {
            e.preventDefault();

            const roleId = target.getAttribute("data-role-id");
            const roleName = target.getAttribute("data-role-name");
            const rolePermissions = JSON.parse(
                target.getAttribute("data-role-permissions")
            );

            const modalTitle = document.querySelector(
                "#addRoleModal .role-title"
            );
            modalTitle.textContent = "Edit Role";

            const roleForm = document.getElementById("addRoleForm");
            roleForm.action = urlUpdateRole.replace(":id", roleId);

            roleForm.querySelector("#name").value = roleName;

            let methodInput = roleForm.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement("input");
                methodInput.type = "hidden";
                methodInput.name = "_method";
                methodInput.value = "PUT";
                roleForm.appendChild(methodInput);
            }

            roleForm
                .querySelectorAll("input[type=checkbox]")
                .forEach((checkbox) => {
                    checkbox.checked = false;
                });

            rolePermissions.forEach((permission) => {
                if (permission == 1) {
                    const selectAllCheckbox = roleForm.querySelector(
                        `input[type=checkbox][id="selectAll"]`
                    );
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = true;
                    }

                    document
                        .querySelectorAll(
                            ".table .form-check-input:not(#selectAll)"
                        )
                        .forEach((checkbox) => {
                            checkbox.checked = true;
                        });
                } else {
                    const checkbox = roleForm.querySelector(
                        `input[type=checkbox][id="${permission}"]`
                    );
                    if (checkbox) {
                        checkbox.checked = true;
                    }
                }
            });

            updateSelectAllCheckbox();

            const addRoleModal = new bootstrap.Modal(
                document.getElementById("addRoleModal")
            );
            addRoleModal.show();
        }
    });
});
