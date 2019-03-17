import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {User} from "../interfaces/user";
import {Observable} from "rxjs";
import {UserDetail} from "../interfaces/user-detail";
import {Answer} from "../interfaces/answer";
import {Match} from "../interfaces/match";

@Injectable()
export class BrowseService {
	constructor(protected http: HttpClient) {
	}

	private userUrl = "api/user/";

	getAllUsers(): Observable<User[]> {
		return (this.http.get<User[]>(this.userUrl));
	}

	getUserByUserId(userId: string): Observable<User[]> {
		return(this.http.get<User[]> (this.userUrl + userId));
	}

	getUserAvatarUrl(userAvatarUrl: string): Observable<User[]> {
		return(this.http.get<User[]>(this.userUrl + userAvatarUrl));
	}

	getUserByUserHandle(userHandle: string): Observable<User[]> {
		return (this.http.get<User[]>(this.userUrl + userHandle));
	}

	getUserDetailAboutMe(userDetailAboutMe: string): Observable<UserDetail[]> {
		return (this.http.get<UserDetail[]>(this.userUrl + userDetailAboutMe));
	}

	getAnswerResult(answerResult: string): Observable<Answer[]> {
		return (this.http.get<Answer[]>(this.userUrl + answerResult));
	}

	getMatchApproved(matchApproved: string): Observable<Match[]> {
		return (this.http.get<Match[]> (this.userUrl + matchApproved));
	}
}