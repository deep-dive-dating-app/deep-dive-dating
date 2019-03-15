import {Component, OnInit} from "@angular/core";
import {SignUpService} from "../shared/services/sign-up.service";
import {FormBuilder, Validators, FormGroup} from "@angular/forms";
import {User} from "../shared/interfaces/user";
import {Router} from "@angular/router";
import {Status} from "../shared/interfaces/status";
import {SignUp} from "../shared/interfaces/sign-up";


@Component({
	templateUrl: ("./sign-up.component.html")
})

export class SignUpComponent implements OnInit {
	signUpForm: FormGroup;
	signUp : SignUp = SignUp(null, null, null, null, null, null, null, null, null, null, null);
	status : Status = {status: null, message: null, type: null};

	constructor(private signUpService : SignUpService, private formBuilder : FormBuilder, private router : Router) {}

	ngOnInit() {
		this.signUpForm = this.formBuilder.group({
			userAvatarUrl: ["", [Validators.maxLength(255), Validators.required]],
			userEmail: ["", [Validators.maxLength(128), Validators.required]],
			userHandle: ["", [Validators.maxLength(32), Validators.required]],
			userPassword: ["", [Validators.maxLength(36), Validators.required]],
			userPasswordConfirm: ["", [Validators.maxLength(36), Validators.required]],
			userDetailAge: ["", [Validators.maxLength(3), Validators.required]],
			userDetailCareer: ["", [Validators.maxLength(32), Validators.required]],
			userDetailEducation: ["", [Validators.maxLength(256), Validators.required]],
			userDetailGender: ["", [Validators.maxLength(32), Validators.required]],
			userDetailRace: ["", [Validators.maxLength(32), Validators.required]],
			userDetailReligion: ["", [Validators.maxLength(128), Validators.required]]
		});
	}

	createSignUp(): void {

		let signUp = new SignUp(this.signUpForm.value.userAvatarUrl, this.signUpForm.value.userEmail, this.signUpForm.value.userHandle, this.signUpForm.value.userPassword, this.signUpForm.value.userPasswordConfirm, this.signUpForm.value.userDetailAge, this.signUpForm.value.userDetailCareer, this.signUpForm.value.userDetailEducation, this.signUpForm.value.userDetailGender, this.signUpForm.value.userDetailRace, this.signUpForm.value.userDetailReligion);

		this.signUpService.createUser(signUp)
			.subscribe(status => {
				this.status = status;

				if(this.status.status === 200) {
					alert(status.message);
					setTimeout(function() {
						$("#signUpForm").modal('hide');
					}, 500);
					this.router.navigate([""]);
				}
			});
	}
}