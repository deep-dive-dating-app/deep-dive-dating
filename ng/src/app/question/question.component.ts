import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators, FormArray} from "@angular/forms";
import {Status} from "../shared/interfaces/status";
import {QuestionService} from "../shared/services/question.service";
import {Router} from "@angular/router";
import {Answer} from "../shared/interfaces/answer";
import {AnswerService} from "../shared/services/answer.service";
import {User} from "../shared/interfaces/user";
import {Question} from "../shared/interfaces/question";
import {UserService} from "../shared/services/user.service";
import {UserDetail} from "../shared/interfaces/user-detail";
import {UserWithUserDetail} from "../shared/interfaces/userWithUserDetail";
import {AuthService} from "../shared/services/auth-service";
import {SignUpService} from "../shared/services/sign-up.service";

@Component({
	templateUrl: "./question.component.html"
})

export class QuestionComponent implements OnInit{
	questionForm : FormGroup;
	question : Question[] = [];
	answer : Answer[] = [];
	status : Status = {status: null, message: null, type: null};

	constructor(private userService : UserService, private questionService: QuestionService, private authService: AuthService, private answerService: AnswerService, private formBuilder : FormBuilder, private router : Router) {}

	ngOnInit() {
		this.getQuestions();
		return this.formBuilder.group({
			questions: this.formBuilder.array([this.createQuestion()])
		})
	}

	createQuestion(): FormGroup {
		return this.questionForm = this.formBuilder.group({
			questionId: ['', [Validators.maxLength(32), Validators.required]],
			answerValue: ['', [Validators.maxLength(32), Validators.required]],
			questionAnswered: ['', [Validators.maxLength(32), Validators.required]]
		})
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
				this.router.navigate(["/user/"]);

			}
		})
	}
	getJwtUserId(): any {
		if(this.authService.decodeJwt()) {
			return this.authService.decodeJwt().auth.userId;
		} else {
			return false;
		}
	}

}