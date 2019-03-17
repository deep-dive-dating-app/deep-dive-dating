import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {User} from "../interfaces/user";
import {Status} from "../interfaces/status";
import {Observable} from "rxjs";
import {UserWithUserDetail} from "../interfaces/userWithUserDetail";

@Injectable()
export class UserService {
	constructor(protected http: HttpClient) {
	}

	private userUrl = "api/user/";

	getAllUsers() : Observable<UserWithUserDetail[]> {
		return(this.http.get<UserWithUserDetail[]>(this.userUrl));
	}

	getUserByUserId(userId: string) : Observable<User> {
		return(this.http.get<User>(this.userUrl + userId));
	}

	getUserByEmail(userEmail: string) : Observable<User> {
		return(this.http.get<User>(this.userUrl + userEmail));
	}

	getUserByUserHandle(userHandle: string) : Observable<User> {
		return(this.http.get<User>(this.userUrl + userHandle));
	}

	getUserByUserActivationToken(userActivationToken : number) : Observable<User> {
		return(this.http.get<User>(this.userUrl + userActivationToken));
	}

	createUser(user: User) : Observable<Status> {
		return(this.http.post<Status>(this.userUrl, user));
	}

	deleteUser(userId: string) : Observable<Status> {
		return(this.http.delete<Status>(this.userUrl + userId));
	}

	editUser(user: User) : Observable<Status> {
		return(this.http.put<Status>(this.userUrl + user.userId, user))
	}
}