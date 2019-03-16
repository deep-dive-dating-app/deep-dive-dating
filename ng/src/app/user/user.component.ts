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

//set the template url and the selector for the ng powered html tag
@Component({
	templateUrl: ("user.component.html"),
	selector: "user"
})

export class UserComponent implements OnInit{

	jwtToken: any = this.jwt.decodeToken(localStorage.getItem("jwt-token"));
	user: User = {userId: null, userHandle: null, userAvatarUrl: null};
	userDetail: UserDetailService[];
	//todo probably add match stuff, answer, question stuff, not sure yet
	match: MatchService[];
	answer: AnswerService = {answerScore: null};
	status: Status = {status:null, message:null, type:null};

	constructor(private userService: UserService, private userDetailService: UserDetailService, private matchService: MatchService, private answerService: AnswerService, private jwt: JwtHelperService){}

	ngOnInit() {
		this.userService.getUserByUserId(this.jwtToken.auth.userId).subscribe(users => this.user = users);
		this.loadUserDetail();
		this.loadMatch();
		this.answerService.getAnswer(this.jwtToken.auth.answerUserId).subscribe(Answers => this.answer = answers);
	}
	loadUserDetail() : void {
		this.userDetailService.getUserDetailByUserId(this.jwtToken.auth.userId).subscribe(UserDetail => this.userDetail = userDetail);
	}
	loadMatches() : void {
		this.matchService.getMatchByMatchUserId(this.jwtToken.auth.userId).subscribe(Match => this.match = matches);
	}

}



