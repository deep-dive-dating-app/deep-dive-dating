import {Component} from "@angular/core";
import {ActivatedRoute, Router} from "@angular/router";
import {SignInService} from "../shared/services/sign-in.service";

@Component({
	templateUrl: "./sign-out.component.html",
	selector: "sign-out"
})

export class SignOutComponent {
	constructor(
		protected router: Router, private signOutService: SignInService
	) {
	};

	ngOnInit() {
		//set session storage for sign up purposes
		//this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
		this.signOutService.getSignOut().subscribe(status => {
			if(status.status === 200) {
				window.localStorage.removeItem("jwt-token")
			this.router.navigate([""]);
			}
		})
	}
}