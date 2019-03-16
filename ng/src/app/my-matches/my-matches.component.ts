import {Component, OnInit} from "@angular/core";
import {Status} from "../shared/interfaces/status";
import {Match} from"../shared/interfaces/match"
import {MatchService} from "../shared/services/match.service";
import {User} from "../shared/interfaces/user"
import {Router} from "@angular/router";


@Component({
	templateUrl: ("my-matches.component.html"),
	selector: "my-matches"
})

export class MyMatchesComponent implements OnInit{
	user : User;
	matches : Match[];
	status : Status = {status: null, message: null, type: null};

	constructor(private matchService : MatchService, private router : Router) {}

	ngOnInit() {
		this.matchService.getMatchByMatchUserId(this.user.userId).subscribe(reply=> this.matches = reply);
	}
}