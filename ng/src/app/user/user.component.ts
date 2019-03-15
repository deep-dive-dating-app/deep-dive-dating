/*

this component is for the user profile page
 */

import {Component, OnInit} from "@angular/core";
import {JwtHelperService} from "@auth0/angular-jwt";
import {User} from "../shared/interfaces/user";
import {Status} from "../shared/interfaces/status";
import {UserService} from "../shared/services/user.service";
import {UserDetail} from "../shared/interfaces/user-detail";
import {UserDetailService} from "../shared/services/user-detail.service";

//set the template url and the selector for the ng powered html tag
@Component({
	templateUrl: ("user.component.html"),
	selector: "user"
})

export class UserComponent implements OnInit{

	jwtToken: any = this.jwt.decodeToken(localStorage.getItem("jwt-token"));
}



