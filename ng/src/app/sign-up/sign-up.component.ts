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

		let signUp : SignUp = {userAvatarUrl:"placeholder.jpg", userEmail: this.signUpForm.value.userEmail, userHandle: this.signUpForm.value.userHandle, userHash: this.signUpForm.value.userHash, userHashConfirm: this.signUpForm.value.userHashConfirm, userDetailAge: this.signUpForm.value.userDetailAge, userDetailCareer: this.signUpForm.value.userDetailCareer, userDetailEducation: this.signUpForm.value.userDetailEducation, userDetailGender: this.signUpForm.value.userDetailGender, userDetailRace: this.signUpForm.value.userDetailRace, userDetailReligion: this.signUpForm.value.userDetailReligion};
		this.signUpService.createUser(signUp)
			.subscribe(status => {
				this.status = status;

				if(this.status.status === 200) {
					alert(status.message);
			}
	})
}}