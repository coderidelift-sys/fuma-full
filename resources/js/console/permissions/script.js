"use strict";

const validateForm = (formSelector, fieldsConfig, rowSelector = ".col-12") => {
    const formElement = document.querySelector(formSelector);
    if (!formElement) return;

    FormValidation.formValidation(formElement, {
        fields: fieldsConfig,
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
                eleValidClass: "",
                rowSelector: rowSelector,
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            autoFocus: new FormValidation.plugins.AutoFocus(),
        },
    })
        .on("core.form.valid", function () {
            formElement.submit();
        })
        .on("plugins.message.placed", function (e) {
            if (e.element.parentElement.classList.contains("input-group")) {
                e.element.parentElement.insertAdjacentElement(
                    "afterend",
                    e.messageElement
                );
            }
        })
        .on("core.element.validated", function (e) {
            if (e.valid) {
                e.element.classList.add("is-valid");
            } else {
                e.element.classList.remove("is-valid");
            }
        });
};

document.addEventListener("DOMContentLoaded", function () {
    const addPermissionFieldsConfig = {
        name: {
            validators: {
                notEmpty: {
                    message: "Please enter permission name",
                },
                stringLength: {
                    min: 3,
                    max: 50,
                    message: "The permission name must be more than 3 and less than 50 characters long",
                },
            },
        },
    };

    const editPermissionFieldsConfig = {
        name: {
            validators: {
                notEmpty: {
                    message: "Please enter permission name",
                },
                stringLength: {
                    min: 3,
                    max: 50,
                    message: "The permission name must be more than 3 and less than 50 characters long",
                },
            },
        },
    };

    validateForm("#addPermissionForm", addPermissionFieldsConfig);
    validateForm("#editPermissionForm", editPermissionFieldsConfig, ".col-sm-9");

    document.addEventListener("click", function (e) {
        const target = e.target.closest(".delete-record");
        if (target) {
            e.preventDefault();

            const form = target.closest("form");
            if (!form) {
                console.error("Form not found for deletion.");
                return;
            }

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
                }
            });
        }
    });

    document.addEventListener("click", function (e) {
        const target = e.target.closest("[data-bs-target='#editPermissionModal']");
        if (target) {
            const id = target.getAttribute("data-bs-id");
            const name = target.getAttribute("data-bs-name");
            const form = document.getElementById("editPermissionForm");
            const permissionNameInput = document.getElementById("editPermissionName");

            if (id && form) {
                form.action = urlUpdatePermission.replace(":id", id);
                permissionNameInput.value = name;
            }
        }
    });
});
