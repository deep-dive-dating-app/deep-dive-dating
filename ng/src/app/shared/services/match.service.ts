import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Match} from "../interfaces/match";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";

@Injectable()
export class MatchService {
	constructor(protected http: HttpClient) {
	}

	private matchUrl = "api/match/";

	getMatchByMatchUserIdAndMatchToUserId(matchUserId : number, matchToUserId : number) : Observable<Match> {
		return(this.http.get<Match>(this.matchUrl + matchUserId + matchToUserId));
	}

	getMatchByMatchToUserId(matchToUserId : number) : Observable<Match[]> {
		return(this.http.get<Match[]>(this.matchUrl + matchToUserId));
	}

	getMatchByMatchUserId(matchUserId : string) : Observable<Match[]> {
		return(this.http.get<Match[]>(this.matchUrl + matchUserId));
	}

	createMatch(match : Match) : Observable<Status> {
		return(this.http.post<Status>(this.matchUrl, match));
	}

	editMatch(match : Match) : Observable<Status> {
		return(this.http.put<Status>(this.matchUrl = match.matchUserId, match));
	}
}