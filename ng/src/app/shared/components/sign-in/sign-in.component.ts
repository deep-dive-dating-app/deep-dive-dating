/*
 this component is for signing up to use the site.
 */

//import needed modules for the sign-up component
import {Component, OnInit, ViewChild} from "@angular/core";
import {Router} from "@angular/router";
import {Status} from "../../interfaces/status";
import {SignIn} from "../../interfaces/sign-in";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignInService} from "../../services/sign-in.service";
import {User} from "../../interfaces/user";
import {UserService} from "../../services/user.service";
import {UserWithUserDetail} from "../../interfaces/userWithUserDetail";
import {UserDetail} from "../../interfaces/user-detail";
import {UserComponent} from "../../../user/user.component";


// set the template url and the selector for the ng powered html tag
@Component({
	templateUrl: "./sign-in.component.html",
	selector: "sign-in"
})

export class SignInComponent implements OnInit{
	users: UserWithUserDetail[] = [];
	user: User = {
		userId: null,
		userActivationToken: null,
		userAgent: null,
		userAvatarUrl: null,
		userBlocked: null,
		userEmail: null,
		userHandle: null,
		userHash: null,
		userIpAddress: null
	};
	//array for userDetail? userDetail: UserDetail[];
	userDetail: UserDetail = {
		userDetailId: null,
		userDetailUserId: null,
		userDetailAboutMe: null,
		userDetailAge: null,
		userDetailCareer: null,
		userDetailDisplayEmail: null,
		userDetailEducation: null,
		userDetailGender: null,
		userDetailInterests: null,
		userDetailRace: null,
		userDetailReligion: null
	};

signInForm : FormGroup;
	status: Status = null;


	constructor( private formBuilder : FormBuilder, private router: Router, private signInService: SignInService) {

	}

	ngOnInit() : void {
		this.signInForm = this.formBuilder.group({
			userEmail: ["", [Validators.maxLength(128), Validators.required]],
			userHash: ["", [Validators.maxLength(36), Validators.required]]
		});

	}

	signIn(): void {

		let signIn: SignIn = {userEmail: this.signInForm.value.userEmail, userPassword: this.signInForm.value.userHash};

		window.localStorage.removeItem("jwt-token");

		this.signInService.postSignIn(signIn)
			.subscribe(status => {
				this.status = status;
			if(status.status === 200) {

				this.router.navigate(["/user/" + status.message]);
				//alert("Sign In Success!");
			} else {
				alert("Incorrect Email or Password. Please Try Again.")
			}
			});
	}
}