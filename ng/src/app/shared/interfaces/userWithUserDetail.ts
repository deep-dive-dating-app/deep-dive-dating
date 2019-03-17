import {User} from "./user";
import {UserDetail} from "./user-detail";

export interface UserWithUserDetail {
	user: User
	userDetail: UserDetail
}