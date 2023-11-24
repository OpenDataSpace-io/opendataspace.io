import { type NextPage } from "next";
import Head from "next/head";
import Image from "next/image";
import Link from "next/link";
import { signIn, type SignInResponse, useSession } from "next-auth/react";
import { useEffect, useState } from "react";
import { useMutation } from "react-query";
import Typography from "@mui/material/Typography";
import Breadcrumbs from "@mui/material/Breadcrumbs";
//import Rating from '@mui/material/Rating';
import FavoriteBorderIcon from "@mui/icons-material/FavoriteBorder";
import FavoriteIcon from "@mui/icons-material/Favorite";

import { type Thing } from "@/types/Thing";
import { useMercure } from "@/utils/mercure";
//import { List as Reviews } from "@/components/review/List";
//import { useOpenLibraryBook } from "@/utils/book";
import { fetch, type FetchError, type FetchResponse } from "@/utils/dataAccess";
//import { type Bookmark } from "@/types/Bookmark";
import { type PagedCollection } from "@/types/collection";
import { Loading } from "@/components/common/Loading";

interface Props {
  data: Thing;
  hubURL: string | null;
  page: number;
}

export const Show: NextPage<Props> = ({ data, hubURL, page }) => {
  const { data: session, status } = useSession();
  //const [bookmark, setBookmark] = useState<Bookmark | null | undefined>();
  //const { data: book, isLoading } = useOpenLibraryBook(data);
  const item = useMercure(data, hubURL);

  /*const bookmarkMutation = useMutation<
    Promise<FetchResponse<Bookmark> | SignInResponse | undefined>,
    Error | FetchError,
    BookmarkProps
    // @ts-ignore
  >(async (data: BookmarkProps) => {
    // @ts-ignore
    if (!session || session?.error === "RefreshAccessTokenError") {
      await signIn("keycloak");

      return;
    }

    if (bookmark) {
      // @ts-ignore
      await deleteBookmark(bookmark["@id"]);
      setBookmark(null);

      return;
    }

    const response: FetchResponse<Bookmark> | undefined = await saveBookmark(data);
    if (response && response?.data) {
      setBookmark(response.data);
    }
  });*/

  // Check in user bookmarks if the current book has been bookmarked
  /*useEffect(() => {
    // /bookmarks endpoint requires authentication
    if (status === "loading" || status === "unauthenticated") return;

    (async () => {
      try {
        const response: FetchResponse<PagedCollection<Bookmark>> | undefined = await fetch(`/bookmarks?book=${data["@id"]}`);
        if (response && response?.data && response.data["hydra:member"]?.length) {
          setBookmark(response.data["hydra:member"][0]);
        }
      } catch (error) {
        console.error(error);
        setBookmark(null);
      }
    })()
  }, [data, status]);*/

  return (
    <div className="container mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
      <Head>
        <title>{`${item["name"]}`}</title>
      </Head>
      <div role="presentation" className="mb-8">
        <Breadcrumbs aria-label="breadcrumb" data-testid="book-breadcrumb">
          <Link href="/things" className="hover:underline">
            Things
          </Link>
          <Typography color="text.primary">{item["name"]}</Typography>
        </Breadcrumbs>
      </div>
      {!!thing && !isLoading && (
        <>
          <div className="flex">
            <div className="min-w-[270px] max-w-[300px] w-full mr-10 text-center">
              {!!thing["images"] && (
                <Image alt={thing["name"]} width={300} height={300} src={thing["images"]["large"]} priority={true} data-testid="book-cover"/>
              ) || (
                <span className="h-40 text-slate-300">No cover</span>
              )}
            </div>
            <div className="w-full">
              <h1 className="font-bold text-2xl text-gray-700">{thing["name"]}</h1>
              <p className="text-gray-600 mt-4" data-testid="book-metadata">
                <span className="flex">
                  {!!thing["dateCreated"] && (
                    <span className="ml-1"> | Created on {thing["dateCreated"]}</span>
                  )}
                </span>
              </p>
              <p className="text-justify leading-7 my-8" data-testid="book-description">
                {thing["description"] ?? "This thing has no description."}
              </p>
            </div>
          </div>
        </>
      ) || (
        <Loading/>
      )}
    </div>
  );
};
