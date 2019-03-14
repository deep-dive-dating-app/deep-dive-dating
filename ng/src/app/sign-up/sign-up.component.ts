import {Component, OnInit} from "@angular/core";
import {SignUpService} from "../shared/services/sign-up.service";
import {FormBuilder, Validators} from "@angular/forms";

@Component({
	template: require("./sign-up.component.html")
})

export class SignUpComponent implements OnInit {
	SignUpForm: FormGroup;
	status : status = {status: null, message: null, type: null}

	constructor(private signUpService : SignUpService, private formBuilder : FormBuilder ) {}

	ngOnInit() {
		this.signUpForm = this.formBuilder.group(
			userHandle : ["", [Validators.maxLength(32), Validators.required]],
			userEmail: ["", [Validators.maxLength(128), Validators.required]],
			userPassword: ["", [Validators.maxLength(36), Validators.required]],
			userConfirmPassword: ["", [Validators.maxLength(36), Validators.required]]
		)
	}
}