import {Component} from '@angular/core';
import {SessionService} from "./shared/services/session.service";
import {Status} from "./shared/interfaces/status";
import {User} from "./shared/interfaces/user";
import {UserService} from "./shared/services/user.service";
import {AuthService} from "./shared/services/auth-service";
import {Router} from "@angular/router";

@Component({
  selector: 'app-root',
  templateUrl: `./app.component.html`,
  styles: []
})

export class AppComponent {
	user: User;
	userId: null;
	router: Router;
	status : Status = null;
	constructor(private sessionService: SessionService, private userService: UserService, private authService: AuthService){
		this.sessionService.setSession().subscribe(reply => this.status = reply);
	}

	getUserId() {
		this.userId = this.authService.decodeJwt().auth.userId;
		this.router.navigate(["/user/", this.userId ]);
	}
}
