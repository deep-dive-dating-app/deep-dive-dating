import {Component} from "@angular/core";
import {ActivatedRoute} from "@angular/router";

@Component ({
	templateUrl: "./sign-out.component.html",
	selector: "sign-out"
})

export class SignOutComponent{
	constructor (
		protected route: ActivatedRoute
	) {};
	ngOnInit() {
		//set session storage for sign up purposes
		this.route.url.subscribe(route => window.sessionStorage.setItem("url", JSON.stringify(route)));
	};
}