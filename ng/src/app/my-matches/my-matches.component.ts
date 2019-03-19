import {Component, OnInit} from "@angular/core";
import {Status} from "../shared/interfaces/status";
import {Match} from"../shared/interfaces/match";
import {MatchService} from "../shared/services/match.service";
import {User} from "../shared/interfaces/user";
import {Router} from "@angular/router";
import {UserWithUserDetail} from "../shared/interfaces/userWithUserDetail";
import {UserDetail} from "../shared/interfaces/user-detail";
import {UserService} from "../shared/services/user.service";


@Component({
	templateUrl: "my-matches.component.html",
})

export class MyMatchesComponent implements OnInit{
	users: UserWithUserDetail[] = [];
	user : User = {
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
	}
	matches : Match[];
	status : Status = {status: null, message: null, type: null};

	constructor(private userService: UserService, private matchService : MatchService, private router : Router) {}

	ngOnInit() {
		this.matchService.getMatchByMatchUserId(this.user.userId).subscribe(reply=> this.matches = reply);
	}
	getUsers() {
		this.userService.getAllUsers().subscribe(reply => this.users= reply)
	}
	getDetailedView(user : UserWithUserDetail) : void {
		this.router.navigate(["/user/", user.user.userId]);
	}
}