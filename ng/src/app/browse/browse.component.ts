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
import {UserDetailService} from "../shared/services/user-detail.service";
import {UserWithUserDetail} from "../shared/interfaces/userWithUserDetail";

@Component({
	templateUrl: "./browse.component.html"

})

export class BrowseComponent implements OnInit {
	users: UserWithUserDetail[] = [];
	user: User = {
		userId: null,
		userActivationToken: null,
		userAgent: null,
		userAvatarUrl: null,
		userBlocked: null,
		userEmail: null,
		userHandle: null,
		userHash: null,
		userIpAddress: null
	};
	//array for userDetail? userDetail: UserDetail[];
	userDetail: UserDetail = {
		userDetailId: null,
		userDetailUserId: null,
		userDetailAboutMe: null,
		userDetailAge: null,
		userDetailCareer: null,
		userDetailDisplayEmail: null,
		userDetailEducation: null,
		userDetailGender: null,
		userDetailInterests: null,
		userDetailRace: null,
		userDetailReligion: null
	};

	constructor( private userService: UserService, private router: Router) {
	}


	ngOnInit() {
		this.getUsers()
	}
	getUsers() {
		this.userService.getAllUsers().subscribe(reply => this.users= reply)
	}
	getDetailedView(user : UserWithUserDetail) : void {
		this.router.navigate(["/user/", user.user.userId]);
	}

}
