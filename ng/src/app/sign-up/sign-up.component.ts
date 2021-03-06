import {Component, OnInit} from "@angular/core";
import {SignUpService} from "../shared/services/sign-up.service";
import {FormBuilder, Validators, FormGroup} from "@angular/forms";
import {User} from "../shared/interfaces/user";
import {Router} from "@angular/router";
import {Status} from "../shared/interfaces/status";
import {SignUp} from "../shared/interfaces/sign-up";
import {CookieService} from "ngx-cookie";
import {FileUploader} from "ng2-file-upload";
import {Observable, from} from "rxjs";


@Component({
	templateUrl: "./sign-up.component.html"
})

export class SignUpComponent implements OnInit {
		signUpForm: FormGroup;
		status : Status = {status: null, message: null, type: null};

		public uploader: FileUploader = new FileUploader(
			{
				itemAlias: 'image',
				url: './api/image/',
				headers: [
					// you will also want to include a JWT-TOKEN
					{name: 'X-XSRF-TOKEN', value: this.cookieService.get('XSRF-TOKEN')}
				],
			}
		);

		cloudinarySecureUrl: string;
		cloudinaryPublicObservable: Observable<string> = new Observable<string>();
		imageUploaded: boolean;

		constructor(private signUpService : SignUpService, private formBuilder : FormBuilder, private router : Router, private cookieService : CookieService) {}

	ngOnInit(): void {
		this.signUpForm = this.formBuilder.group({
			userEmail: ["", [Validators.maxLength(128), Validators.required, Validators.email]],
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

	uploadImage(): void{
		this.uploader.uploadAll();
		this.cloudinaryPublicObservable.subscribe(cloudinaryUrl =>this.cloudinarySecureUrl= cloudinaryUrl );
				this.uploader.onSuccessItem = (item: any, response: string, status: number, headers: any) => {
					let reply = JSON.parse(response);
					this.cloudinarySecureUrl = reply.message;
					this.cloudinaryPublicObservable = from(this.cloudinarySecureUrl);
					if (this.cloudinarySecureUrl) {
						console.log("bullshot")
					}

				};
	}

	postSignUp(): void {

		let signUp : SignUp = {userAvatarUrl: this.cloudinarySecureUrl, userEmail: this.signUpForm.value.userEmail, userHandle: this.signUpForm.value.userHandle, userPassword: this.signUpForm.value.userPassword, userPasswordConfirm: this.signUpForm.value.userPasswordConfirm, userDetailAge: this.signUpForm.value.userDetailAge, userDetailCareer: this.signUpForm.value.userDetailCareer, userDetailEducation: this.signUpForm.value.userDetailEducation, userDetailGender: this.signUpForm.value.userDetailGender, userDetailRace: this.signUpForm.value.userDetailRace, userDetailReligion: this.signUpForm.value.userDetailReligion};
		this.signUpService.createUser(signUp)
			.subscribe(status => {
				this.status = status;

				if(this.status.status === 200) {
					// alert(status.message);
					this.router.navigate(["/question/"]);

				}
	})
}
}