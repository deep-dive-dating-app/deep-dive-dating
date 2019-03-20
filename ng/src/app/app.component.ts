import {Component} from '@angular/core';
import {SessionService} from "./shared/services/session.service";
import {Status} from "./shared/interfaces/status";
import {User} from "./shared/interfaces/user";
import {UserService} from "./shared/services/user.service";
import {AuthService} from "./shared/services/auth-service";
import {Router} from "@angular/router";
import {Title} from "@angular/platform-browser";

@Component({
  selector: 'app-root',
  templateUrl: `./app.component.html`,
  styles: []
})

export class AppComponent {
	userId : null;
	router: Router;
	status : Status = null;
	constructor(private sessionService: SessionService, private userService: UserService, private authService: AuthService, private titleService: Title){
		this.sessionService.setSession().subscribe(reply => this.status = reply);
		this.titleService.setTitle( "Dan's Den" );
	}

	getUserId() {
		this.userId = this.authService.decodeJwt().auth.userId;
		return
	}
}