import {Component, OnInit} from "@angular/core";
import {BrowseService} from "../shared/services/browse.service";
import {FormBuilder, Validators, FormGroup} from "@angular/forms";
import {Browse} from "../shared/interfaces/browse";
import {Router} from "@angular/router";
import {Status} from "../shared/interfaces/status";
import {Browse} from "../shared/interfaces/browse";
import {SignUpService} from "../shared/services/sign-up.service";

@Component({
	templateUrl: ("./browse.component.html")

})

export class BrowseComponent implements OnInit {
	userHandle: string =
	answerResult:
	status : Status = {status: null, message : null, type: null};
	browse : Browse = {userHandle: null}
	constructor(private browseService)


	ngOnInit() {
		this.browseService.getBrowseByUserHandle(this.userHandle).subscribe(browse=>this.browse.userHandle)
		this.getUserHandle
	}
	getAnswerResult(): void{
			this.browseService.getAnswerByAnswerResult(this.answerResult).subscribe(answerResult =>this.answerResult = answerResult)
	}
}
