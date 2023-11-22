import NextAuth, { type AuthOptions, type SessionOptions, type DefaultUser, type TokenSet } from "next-auth";
//import KeycloakProvider from "next-auth/providers/keycloak";
import GitHubProvider from "next-auth/providers/github";
//import { OIDC_CLIENT_ID, OIDC_SERVER_URL } from "@/config/keycloak";
import { OPENSTREETMAP_CLIENT_ID, OPENSTREETMAP_CLIENT_SECRET } from "@/config/openstreetmap";
import { GITHUB_CLIENT_ID, GITHUB_SECRET } from "@/config/github";


interface Session extends SessionOptions {
  accessToken: string
  idToken: string
  error?: "RefreshAccessTokenError"
  user?: User
}

interface User extends DefaultUser {
  sub?: string | null
}

interface JWT {
  accessToken: string
  idToken: string
  expiresAt: number
  refreshToken: string
  error?: "RefreshAccessTokenError"
  sub?: any
}

interface Account {
  access_token: string
  id_token: string
  expires_in: number
  refresh_token: string
}

export const authOptions: AuthOptions = {
  providers: [
    /*KeycloakProvider({
      id: 'keycloak',
      clientId: OIDC_CLIENT_ID,
      issuer: OIDC_SERVER_URL,
      authorization: {
        // https://authjs.dev/guides/basics/refresh-token-rotation#jwt-strategy
        params: {
          access_type: "offline",
          prompt: "consent",
        },
      },
      // https://github.com/nextauthjs/next-auth/issues/685#issuecomment-785212676
      protection: "pkce",
      // https://github.com/nextauthjs/next-auth/issues/4707
      // @ts-ignore
      clientSecret: null,
      client: {
        token_endpoint_auth_method: "none"
      },
    }),*/
    GitHubProvider({
      clientId: GITHUB_CLIENT_ID,
      clientSecret: GITHUB_SECRET
    }),
    {
      id: "openstreetmap",
      name: "OpenStreetMap",
      type: "oauth",
      version: "2.0",
      //scope: "read_prefs",
      authorization: {
        params: { grant_type: "authorization_code" },
      },
      token: "https://www.openstreetmap.org/oauth/request_token",
      accessTokenUrl: "https://www.openstreetmap.org/oauth/request_token",
      requestTokenUrl: "https://www.openstreetmap.org/oauth/access_token",
      //authorizationUrl: "https://www.openstreetmap.org/oauth/authorize",
      profileUrl: "https://api.openstreetmap.org/api/0.6/user/details",
      //userinfo: "https://api.openstreetmap.org/api/0.6/user/details",
      clientId: OPENSTREETMAP_CLIENT_ID,
      clientSecret: OPENSTREETMAP_CLIENT_SECRET,
      profile: (profile) => {
        return {
          id: profile.id,
          name: profile.display_name,
          email: profile.email,
          image: null,
        };
      },
    },
  ],
  callbacks: {
    // @ts-ignore
    /*async jwt({ token, account }: { token: JWT, account: Account }): Promise<JWT> {
      if (account) {
        // Save the access token and refresh token in the JWT on the initial login
        return {
          ...token,
          accessToken: account.access_token,
          idToken: account.id_token,
          expiresAt: Math.floor(Date.now() / 1000 + account.expires_in),
          refreshToken: account.refresh_token,
        };
      } else if (Date.now() < token.expiresAt * 1000) {
        // If the access token has not expired yet, return it
        return token;
      } else {
        // If the access token has expired, try to refresh it
        try {
          // todo use .well-known
          /*const response = await fetch(`${OIDC_SERVER_URL}/protocol/openid-connect/token`, {
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
              client_id: OIDC_CLIENT_ID,
              grant_type: "refresh_token",
              refresh_token: token.refreshToken,
            }),
            method: "POST",
          });*/
          /*
          const tokens: TokenSet = await response.json();

          if (!response.ok) throw tokens;

          return {
            ...token, // Keep the previous token properties
            // @ts-ignore
            accessToken: tokens.access_token,
            // @ts-ignore
            idToken: tokens.id_token,
            // @ts-ignore
            expiresAt: Math.floor(Date.now() / 1000 + tokens.expires_at),
            // Fall back to old refresh token, but note that
            // many providers may only allow using a refresh token once.
            refreshToken: tokens.refresh_token ?? token.refreshToken,
          };
        } catch (error) {
          console.error("Error refreshing access token", error);
          // The error property will be used client-side to handle the refresh token error
          return {
            ...token,
            error: "RefreshAccessTokenError" as const
          };
        }
      }
    },*/
    /*
    // @ts-ignore
    async session({ session, token }: { session: Session, token: JWT }): Promise<Session> {
      // Save the access token in the Session for API calls
      if (token) {
        session.accessToken = token.accessToken;
        session.idToken = token.idToken;
        session.error = token.error;
        if (session?.user && token?.sub) {
          session.user.sub = token.sub;
        }
      }

      return session;
    }*/
  },
  
};

export default NextAuth(authOptions);
