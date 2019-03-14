import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {Misquote} from "../interfaces/misquote";
import {Status} from "../interfaces/status";

@Injectable()
export class AnswerService {

	constructor(protected http: HttpClient) {}

	private misquoteUrl = "api/answer/";

	deleteMisquote(answerId: string) : Observable<Status> {
		return(this.http.delete<Status>(this.answerUrl + answerId));
	}

	getAllAnswers() : Observable<Answer[]> {
		return(this.http.get<Answer[]>(this.answerUrl));
	}

	getAnswer(answerId: string) : Observable<Misquote> {
		return(this.http.get<Answer>(this.misquoteUrl + answerId));
	}

	createAnswer(answer: Answer) : Observable<Status> {
		return(this.http.post<Status>(this.answerUrl, answer));
	}

	editAnswer(answer: Answer) : Observable<Status> {
		return(this.http.put<Status>(this.answerUrl + answer.answerId, answer));
	}
}