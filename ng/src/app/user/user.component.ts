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
import {MatchService} from "../shared/services/match.service";
import {AnswerService} from "../shared/services/answer.service";
import {Answer} from "../shared/interfaces/answer";
import {Match} from "../shared/interfaces/match";
import {ActivatedRoute} from "@angular/router";

//set the template url and the selector for the ng powered html tag
@Component({
	templateUrl: "./user.component.html",
	selector: "user"
})

export class UserComponent implements OnInit{

	userId = this.activatedRoute.snapshot.params["userId"];

	jwtToken: any = this.jwt.decodeToken(localStorage.getItem("jwt-token"));
	user: User;
	userDetail: UserDetail;
	// match: Match[];
	// answer: Answer;
	status: Status = {status:null, message:null, type:null};

	constructor(private userService: UserService, private userDetailService: UserDetailService, private activatedRoute: ActivatedRoute, private jwt: JwtHelperService){}

	ngOnInit() {
		this.userService.getUserByUserId(this.userId).subscribe(users => this.user = users);
		this.loadUserDetail();
	}

	loadUserDetail() : void {
		this.userDetailService.getUserDetailByUserId(this.userId).subscribe(userDetail => this.userDetail = userDetail);
	}


}



