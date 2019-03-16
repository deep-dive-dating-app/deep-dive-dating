import {Component, OnInit} from "@angular/core";
import {BrowseService} from "../shared/services/browse.service";
import {FormBuilder, Validators, FormGroup} from "@angular/forms";
import {ActivatedRoute, Router} from "@angular/router";
import {Status} from "../shared/interfaces/status";
import {User} from "../shared/interfaces/user"
import {UserService} from "../shared/services/user.service";
import {Answer} from "../shared/interfaces/answer";
import {Match} from "../shared/interfaces/match";
import {AnswerService} from "../shared/services/answer.service";
import {MatchService} from "../shared/services/match.service";
import {UserDetail} from "../shared/interfaces/user-detail";

@Component({
	templateUrl: ("./browse.component.html")

})

export class BrowseComponent implements OnInit {
	userId: string = this.route.snapshot.params["userId"];
	//array for user?
	user: User = {userId: null, userActivationToken: null, userAgent: null, userAvatarUrl: null, userBlocked: null, userEmail: null, userHandle: null, userHash: null, userIpAddress: null};
	//array for userDetail?
	userDetail: UserDetail;
	//array?
	answer: Answer = {answerQuestionId: null, answerUserId: null, answerResult: null, answerScore: null};
	match: Match[];
	status: Status = {status: null, message : null, type: null};
	constructor(private browseService: BrowseService, private userService: UserService, private answerService: AnswerService, private matchService: MatchService, private route: ActivatedRoute){}


	ngOnInit() {
		//userbyUserId need to add to service.ts
		this.browseService.getUserByUserId(this.userId).subscribe(browse=> this.user = browse);
		this.getUserHandle();
		//get answerResult
		//get userDetailAboutMe
		//get match to, get match
		//get user avatar?

	}
	getUserHandle() : void{
		this.userService.getAllUsers(this.userId).suscribe()
	}
	getAllUsers(): void {
		this.userService.getAllUsers(this.user).subscribe(browse => this.user = browse)
	}
	//get answerResult
	//get userDetailAboutMe

}
