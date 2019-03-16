import {Component, OnInit} from "@angular/core";
import {BrowseService} from "../shared/services/browse.service";
import {FormBuilder, Validators, FormGroup} from "@angular/forms";
import {Browse} from "../shared/interfaces/browse";
import {Router} from "@angular/router";
import {Status} from "../shared/interfaces/status";
import {User} from "../shared/interfaces/user"

@Component({
	templateUrl: ("./browse.component.html")

})

export class BrowseComponent implements OnInit {
	userHandle: string = 	this.route.snapshot.params["userHandle"];
	status: Status = {status: null, message : null, type: null};
	browse : Browse = {userHandle: null};
	constructor(private browseService: BrowseService, private userService: UserService, private route: ActivatedRoute){}


	ngOnInit() {
		this.browseService.getUserByUserHandle(this.userHandle).subscribe(browse=> this.browse = browse);
		this.getUserHandle();
	}

	getAllUsers(): void {
		this.userService.getAllUsers(this.userHandle).subscribe(browse=> this.user = browse);
	}
}
