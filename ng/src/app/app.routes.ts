import {RouterModule, Routes} from "@angular/router";
import {SplashComponent} from "./splash/splash.component";
import{AppComponent} from "./app.component";
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


export const allAppComponents = [AppComponent, SplashComponent, SignInComponent, SignUpComponent, MyMatchesComponent, AboutUsComponent, UserComponent, BrowseComponent];

export const routes: Routes = [
	{path: "", component: SplashComponent},
	{path: "sign-in", component: SignInComponent},
	{path: "sign-up", component: SignUpComponent},
	{path: "my-matches", component: MyMatchesComponent},
	{path: "about-us", component: AboutUsComponent},
	{path: "user", component: UserComponent},
	{path: "browse", component: BrowseComponent}
];

export const appRoutingProviders: any[] = [
	SessionService,
	SignInService,
	SignUpComponent,
	MyMatchesComponent,
	AboutUsComponent,
	BrowseComponent,
	MatchService
];

export const routing = RouterModule.forRoot(routes);