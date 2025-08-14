"use strict";

document.addEventListener("DOMContentLoaded", function (e) {
    (function () {
        const validateForm = (formSelector, fieldsConfig) => {
            const formElement = document.querySelector(formSelector);
            if (!formElement) return;

            const formValidationInstance = FormValidation.formValidation(
                formElement,
                {
                    fields: fieldsConfig,
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            eleValidClass: "",
                            rowSelector: ".col-md-6",
                        }),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        autoFocus: new FormValidation.plugins.AutoFocus(),
                    },
                    init: (instance) => {
                        instance.on("plugins.message.placed", function (e) {
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

                        // Add `is-valid` class to valid fields
                        instance.on("core.element.validated", function (e) {
                            if (e.valid) {
                                e.element.classList.add("is-valid");
                            }
                        });

                        // Manually submit the form after validation
                        instance.on("core.form.valid", function () {
                            formElement.submit();
                        });
                    },
                }
            );
        };

        // Form validation for Change Password
        validateForm("#formUpdatePassword", {
            current_password: {
                validators: {
                    notEmpty: {
                        message: "Please enter your current password",
                    },
                    stringLength: {
                        min: 8,
                        message: "Password must be more than 8 characters",
                    },
                },
            },
            password: {
                validators: {
                    notEmpty: {
                        message: "Please enter new password",
                    },
                    stringLength: {
                        min: 8,
                        message: "Password must be more than 8 characters",
                    },
                },
            },
            password_confirmation: {
                validators: {
                    notEmpty: {
                        message: "Please confirm your new password",
                    },
                    identical: {
                        compare: function () {
                            return document.querySelector(
                                '#formUpdatePassword [name="password"]'
                            ).value;
                        },
                        message:
                            "The password and its confirmation do not match",
                    },
                    stringLength: {
                        min: 8,
                        message: "Password must be more than 8 characters",
                    },
                },
            },
        });

        // Form validation for Account Settings
        validateForm("#formAccountSettings", {
            name: {
                validators: {
                    notEmpty: {
                        message: "Please enter your name",
                    },
                    stringLength: {
                        min: 2,
                        message: "Name must be at least 2 characters long",
                    },
                },
            },
            email: {
                validators: {
                    notEmpty: {
                        message: "Please enter your email address",
                    },
                    emailAddress: {
                        message: "Please enter a valid email address",
                    },
                },
            },
            role_id: {
                validators: {
                    notEmpty: {
                        message: "Please select a role",
                    },
                },
            },
        });
    })();
});

document.addEventListener("DOMContentLoaded", function () {
    const uploadInput = document.getElementById("upload");
    const resetButton = document.querySelector(".account-image-reset");
    const avatarImage = document.getElementById("uploadedAvatar");

    uploadInput.addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                avatarImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    resetButton.addEventListener("click", function () {
        avatarImage.src = defaultImagePath;
        uploadInput.value = "";
    });
});
