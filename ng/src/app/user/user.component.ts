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

//set the template url and the selector for the ng powered html tag
@Component({
	templateUrl: ("user.component.html"),
	selector: "user"
})

export class UserComponent implements OnInit{

	jwtToken: any = this.jwt.decodeToken(localStorage.getItem("jwt-token"));
	user: User;
	userDetail: UserDetail;
	match: Match[];
	answer: Answer;
	status: Status = {status:null, message:null, type:null};

	constructor(private userService: UserService, private userDetailService: UserDetailService, private matchService: MatchService, private answerService: AnswerService, private jwt: JwtHelperService){}

	ngOnInit() {
		this.userService.getUserByUserId(this.jwtToken.auth.userId).subscribe(users => this.user = users);
		this.loadAnswers();
		this.loadUserDetail();
		this.loadMatches();
	}
	loadAnswers() : void {
		this.answerService.getAnswer(this.jwtToken.auth.answerUserId).subscribe(answers => this.answer = answers);
	}
	loadUserDetail() : void {
		this.userDetailService.getUserDetailByUserId(this.jwtToken.auth.userId).subscribe(userDetail => this.userDetail = userDetail);
	}
	loadMatches() : void {
		this.matchService.getMatchByMatchUserId(this.jwtToken.auth.userId).subscribe(match => this.match = match);
	}

}



