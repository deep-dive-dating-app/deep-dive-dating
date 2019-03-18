/*
 this component is for signing up to use the site.
 */

//import needed modules for the sign-up component
import {Component, OnInit, ViewChild,} from "@angular/core";
import {Router} from "@angular/router";
import {Status} from "../../interfaces/status";
import {SignIn} from "../../interfaces/sign-in";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignInService} from "../../services/sign-in.service";


// set the template url and the selector for the ng powered html tag
@Component({
	templateUrl: "./sign-in.component.html",
	selector: "sign-in"
})

export class SignInComponent implements OnInit{

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
				this.signInForm.reset();
				console.log("Sign In Success!");
			} else {
				alert("Incorrect Email or Password. Please Try Again.")
			}
			});
	}
}