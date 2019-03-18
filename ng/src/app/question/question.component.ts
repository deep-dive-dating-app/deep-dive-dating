import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Status} from "../shared/interfaces/status";
import {QuestionService} from "../shared/services/question.service";
import {Router} from "@angular/router";
import {Answer} from "../shared/interfaces/answer";
import {AnswerService} from "../shared/services/answer.service";
import {User} from "../shared/interfaces/user";
import {Question} from "../shared/interfaces/question";
import {UserService} from "../shared/services/user.service";

@Component({
	templateUrl: "./question.component.html"
})

export class QuestionComponent implements OnInit{
	questionForm : FormGroup;
	question : Question[] = [];
	answer : Answer[] = [];
	status : Status = {status: null, message: null, type: null};

	constructor(private userService : UserService, private questionService: QuestionService, private answerService: AnswerService, private formBuilder : FormBuilder, private router : Router) {}

	ngOnInit() {
		this.getQuestions();

	}

	getQuestions () {
		this.questionService.getAllQuestions();
	}


	postAnswer(): void {
		let answer : Answer = null;
		this.answerService.createAnswer(answer).subscribe(status => {
			this.status = status;

			if(this.status.status === 200) {
				alert(status.message);
			}
		})
	}
}