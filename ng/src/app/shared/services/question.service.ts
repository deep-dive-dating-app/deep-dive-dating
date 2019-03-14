import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {Question} from "../interfaces/question";
import {Status} from "../interfaces/status";

@Injectable()
export class QuestionService {

	constructor(protected http: HttpClient) {}

	private questionUrl = "api/question/";

	getQuestion(questionId: string) : Observable<Question> {
		return(this.http.get<Question>(this.questionUrl + questionId));
	}
	createQuestion(question: Question) : Observable<Status> {
		return(this.http.post<Status>(this.questionUrl, question));
	}
	getAllQuestions() : Observable<Question[]> {
		return(this.http.get<Question[]>(this.questionUrl));
	}
	editQuestion(question: Question) : Observable<Status> {
		return(this.http.put<Status>(this.questionUrl + question.questionId, question));
	}
}