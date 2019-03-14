import {HttpClient} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {Answer} from "../interfaces/answer";
import {Status} from "../interfaces/status";

@Injectable()
export class AnswerService {

	constructor(protected http: HttpClient) {}

	private answerUrl = "api/answer/";

	deleteAnswer(answerId: string) : Observable<Status> {
		return(this.http.delete<Status>(this.answerUrl + answerId));
	}

	getAllAnswers() : Observable<Answer[]> {
		return(this.http.get<Answer[]>(this.answerUrl));
	}

	getAnswer(answerId: string) : Observable<Answer> {
		return(this.http.get<Answer>(this.answerUrl + answerId));
	}


	AnswerByAnswerQuestionIdAndUserId(AnswerQuestionId: string,UserId ) : Observable<Answer> {
		return(this.http.get<Answer>(this.answerUrl + AnswerQuestionId + UserId));

	}

	createAnswer(answer: Answer) : Observable<Status> {
		return(this.http.post<Status>(this.answerUrl, answer));
	}

	editAnswer(answer: Answer) : Observable<Status> {
		return(this.http.put<Status>(this.answerUrl + answer.answerQuestionId, answer));
	}
}