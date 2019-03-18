import {RouterModule, Routes} from "@angular/router";
import {SplashComponent} from "./splash/splash.component";
import {AppComponent} from "./app.component";
import {SignInService} from "./shared/services/sign-in.service";
import {SignInComponent} from "./shared/components/sign-in/sign-in.component";
import {SessionService} from "./shared/services/session.service";
import {SignUpComponent} from "./sign-up/sign-up.component";
import {MyMatchesComponent} from "./my-matches/my-matches.component";
import {AboutUsComponent} from "./about-us/about-us.component";
import {APP_BASE_HREF} from "@angular/common";
import {UserComponent} from "./user/user.component";
import {BrowseComponent} from "./browse/browse.component";
import {MatchService} from "./shared/services/match.service";
import {UserService} from "./shared/services/user.service";
import {UserDetailService} from "./shared/services/user-detail.service";
import {SignUpService} from "./shared/services/sign-up.service";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
import {DeepDiveInterceptor} from "./shared/interceptors/deep-dive.interceptor";
import {JwtHelperService} from "@auth0/angular-jwt";
import {QuestionComponent} from "./question/question.component";
import {QuestionService} from "./shared/services/question.service";

import {SignOutComponent} from "./sign-out/sign-out.component";
import {SignOutService} from "./shared/services/sign-out.service";


export const allAppComponents = [AppComponent, SplashComponent, SignInComponent, SignUpComponent, SignOutComponent, MyMatchesComponent, AboutUsComponent, UserComponent, BrowseComponent, QuestionComponent];

export const routes: Routes = [
	{path: "", component: SplashComponent},
	{path: "sign-in", component: SignInComponent},
	{path: "sign-up", component: SignUpComponent},
	{path: "my-matches", component: MyMatchesComponent},
	{path: "about-us", component: AboutUsComponent},
	{path: "user/:userId", component: UserComponent},
	{path: "browse", component: BrowseComponent},
	{path: "question", component: QuestionComponent},
	{path: "sign-out", component: SignOutComponent}
];

export const appRoutingProviders: any[] = [
	SessionService,
	SignInService,
	MatchService, UserService, UserDetailService, SignUpService, SessionService, JwtHelperService, SignOutService,
	{provide: HTTP_INTERCEPTORS, useClass: DeepDiveInterceptor, multi: true}
];

export const routing = RouterModule.forRoot(routes);
