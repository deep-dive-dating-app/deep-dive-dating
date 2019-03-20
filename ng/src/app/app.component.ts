import {Component} from '@angular/core';
import {SessionService} from "./shared/services/session.service";
import {Status} from "./shared/interfaces/status";
import {User} from "./shared/interfaces/user";
import {UserService} from "./shared/services/user.service";
import {AuthService} from "./shared/services/auth-service";
import {Router} from "@angular/router";
import {Title} from "@angular/platform-browser";
import {JwtHelperService} from "@auth0/angular-jwt";

@Component({
  selector: 'app-root',
  templateUrl: `./app.component.html`,
  styles: []
})

export class AppComponent {
	user: User = null;
	userId: string;
	show: boolean = true;
	router: Router;
	status : Status = null;
	constructor(private sessionService: SessionService, private userService: UserService, private authService: AuthService, private titleService: Title, private jwtHelper: JwtHelperService) {
		this.userId = this.jwtHelper.decodeToken(localStorage.getItem("jwt-token"));
		this.sessionService.setSession().subscribe(reply => this.status = reply);
		this.getUserId();
		this.titleService.setTitle( "Dan's Den" );
	}

	getUserId() {
		let token = localStorage.getItem("jwt-token");
		token !== null ? this.userId = this.jwtHelper.decodeToken().auth.userId : this.userId = null

	}
}