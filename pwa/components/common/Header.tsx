import { signIn, useSession } from "next-auth/react";
import { useRouter } from "next/router";
import Link from "next/link";
import { MenuItem, Select } from "@mui/material";
import PersonOutlineIcon from "@mui/icons-material/PersonOutline";
import FavoriteBorderIcon from "@mui/icons-material/FavoriteBorder";
import { useTranslation } from 'next-i18next';

import { signOut } from "@/utils/security";

export const Header = () => {
  const router = useRouter();
  const { data: session } = useSession();
  const { t, i18n } = useTranslation('common');

  const clientSideLanguageChange = (newLocale: string) => {
    i18n.changeLanguage(newLocale);
    router.push(router.asPath, router.asPath, { locale: newLocale });
  }

  if (router.pathname === "/" || router.pathname.match(/^\/admin/)) return <></>;

  return (
    <header className="bg-neutral-100 sticky top-0 z-10">
      <nav className="container mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8" aria-label="Global">
        <div className="block text-4xl font-bold">
          <Link href="/things" className="text-gray-700 hover:text-gray-900">
            OpenDataSpace
          </Link>
        </div>
        <div className="lg:flex lg:flex-1 lg:justify-end lg:gap-x-12">
          {/* Language Selection */}
          <Select
            data-testid="language"
            variant="standard"
            value={i18n.language}
            onChange={(e: React.ChangeEvent<HTMLSelectElement>) => clientSideLanguageChange(e.target.value)}
          >
            <MenuItem value="en">English</MenuItem>
            <MenuItem value="de">Deutsch</MenuItem>
          </Select>
        </div>
        <div className="lg:flex lg:flex-1 lg:justify-end lg:gap-x-12">
          {/* @ts-ignore */}
          {!!session && !session.error && (
            <a href="#" className="font-semibold text-gray-900" role="menuitem" onClick={(e) => {
              e.preventDefault();
              signOut(session);
            }}>
              {t('signout')}
            </a>
          ) || (
            <a href="#" className="font-semibold text-gray-900" role="menuitem" onClick={(e) => {
              e.preventDefault();
              signIn("keycloak");
            }}>
              <PersonOutlineIcon className="w-6 h-6 mr-1"/>
              {t('login')}
            </a>
          )}
        </div>
      </nav>
    </header>
  )
}

//export default Header;