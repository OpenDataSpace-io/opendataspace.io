import { type NextPage } from "next";
import Head from "next/head";
import Image from "next/image";
import Link from "next/link";
import { useEffect, useState } from "react";
import { useMutation } from "react-query";
import Typography from "@mui/material/Typography";
import Breadcrumbs from "@mui/material/Breadcrumbs";
import FavoriteBorderIcon from "@mui/icons-material/FavoriteBorder";
import FavoriteIcon from "@mui/icons-material/Favorite";

import { type Thing } from "@/types/Thing";
import { useMercure } from "@/utils/mercure";
import { useThing } from "@/utils/thing";
import { fetch, type FetchError, type FetchResponse } from "@/utils/dataAccess";
import { type PagedCollection } from "@/types/collection";
import { Loading } from "@/components/common/Loading";

interface Props {
  data: Thing;
  hubURL: string | null;
  page: number;
}

export const Show: NextPage<Props> = ({ data, hubURL, page }) => {
  
  const item = useMercure(data, hubURL);

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
      {!!item && (
        <>
          <div className="flex">
            <div className="min-w-[270px] max-w-[300px] w-full mr-10 text-center">
              {!!item["images"] && (
                <Image alt={item["name"]} width={300} height={300} src={item["images"]["large"]} priority={true} data-testid="book-cover"/>
              ) || (
                <span className="h-40 text-slate-300">No cover</span>
              )}
            </div>
            <div className="w-full">
              <h1 className="font-bold text-2xl text-gray-700">{item["name"]}</h1>
              <p className="text-gray-600 mt-4" data-testid="book-metadata">
                <span className="flex">
                  {!!item["dateCreated"] && (
                    <span className="ml-1"> | Created on {item["dateCreated"]}</span>
                  )}
                </span>
              </p>
              <p className="text-justify leading-7 my-8" data-testid="book-description">
                {item["properties"]["description"] ?? "This thing has no description."}
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