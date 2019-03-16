import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {User} from "../interfaces/user";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";

@Injectable()
export class BrowseService {
	constructor(protected http: HttpClient) {
	}

	private userUrl = "api/user/";

	getAllUsers(): Observable<User[]> {
		return (this.http.get<User[]>(this.userUrl));
	}

	getUserByUserHandle(userHandle: string): Observable<User[]> {
		return (this.http.get<User[]>(this.userUrl + userHandle));
	}
}