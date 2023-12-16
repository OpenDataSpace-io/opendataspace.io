import * as React from 'react';
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
import Button from "@mui/material/Button";
import ButtonGroup from "@mui/material/ButtonGroup";

import { type Thing } from "@/types/Thing";
import { useMercure } from "@/utils/mercure";
import { useThing } from "@/utils/thing";
import { fetch, type FetchError, type FetchResponse } from "@/utils/dataAccess";
import { type PagedCollection } from "@/types/collection";
import { Loading } from "@/components/common/Loading";
//import { ExportMenu } from "@/components/common/Export";
import Menu from '@mui/material/Menu';
import MenuItem from '@mui/material/MenuItem';

interface Props {
  data: Thing;
  hubURL: string | null;
  page: number;
}

export const Show: NextPage<Props> = ({ data, hubURL, page }) => {
  
  const item = useMercure(data, hubURL);

  const [anchorEl, setAnchorEl] = React.useState<null | HTMLElement>(null);
  const open = Boolean(anchorEl);
  const handleClick = (event: React.MouseEvent<HTMLButtonElement>) => {
    setAnchorEl(event.currentTarget);
  };
  const handleClose = () => {
    setAnchorEl(null);
  };


  return (
    <div className="container mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
      <Head>
        <title>{`${item["name"]}`}</title>
      </Head>
      <div role="presentation" className="mb-8">
        <Breadcrumbs aria-label="breadcrumb" data-testid="thing-breadcrumb">
          <Link href="/things" className="hover:underline">
            Things
          </Link>
          <Typography color="text.primary">{item["name"]}</Typography>
        </Breadcrumbs>
        <div className="lg:flex lg:flex-1 lg:justify-end lg:gap-x-12">
        <ButtonGroup variant="contained" aria-label="outlined primary button group">
                <Button href={item['@id']+"/edit"}>Edit</Button>
                <Button href={item['@id']+"/preview"}>Preview</Button>
                <Button href={item['@id']+"/history"}>History</Button>
              </ButtonGroup>
              <Button
                id="basic-button"
                aria-controls={open ? 'basic-menu' : undefined}
                aria-haspopup="true"
                aria-expanded={open ? 'true' : undefined}
                onClick={handleClick}
              >
                Export
              </Button>
              <Menu
                id="basic-menu"
                anchorEl={anchorEl}
                open={open}
                onClose={handleClose}
                MenuListProps={{
                  'aria-labelledby': 'basic-button',
                }}
              >
                <MenuItem onClick={handleClose}>
                  <Link href={item['@id']+".json"}>Json</Link>
                </MenuItem>
                <MenuItem href={item['@id']+".jsonld"} onClick={handleClose}>
                  <Link href={item['@id']+".jsonld"}>JsonLD</Link>
                </MenuItem>
              </Menu>
        </div>
      </div>
      {!!item && (
        <>
          <div className="flex">
            <div className="min-w-[270px] max-w-[300px] w-full mr-10 text-center">
              {!!item["image"] && (
                <Image alt={item["name"]} width={300} height={300} src={item["image"]} priority={true} data-testid="thing-cover"/>
              ) || (
                <span className="h-40 text-slate-300">No image</span>
              )}
            </div>
            <div className="w-full">
              <h1 className="font-bold text-2xl text-gray-700">{item["name"]}</h1>
              <h2>Data View</h2>
              <div className="text-gray-600 mt-4" data-testid="thing-metadata">
                {Object.entries(item).map(([key, value]) => {
                  if (Array.isArray(value)) {
                    return (
                      <span key={key}>
                        {value.map((item, index) => (
                            <span key={index} className="ml-1">
                              <h3>{key}[{index}]:</h3>
                              {JSON.stringify(item)}
                            </span>
                        ))}
                      </span>
                    );
                  } else if (typeof value === 'object' && value !== null) {
                    return (
                      <span key={key}>
                        {Object.entries(value).map(([subKey, subValue]) => (
                            <span key={subKey} className="ml-1">
                              <h3>{key}.{subKey}:</h3>
                              {JSON.stringify(subValue)}
                            </span>
                        ))}
                      </span>
                    );
                  } else {
                    return (
                      <span key={key}>
                          <span className="ml-1">
                            <h3>{key}:</h3>
                            {value}
                          </span>
                      </span>
                    );
                  }
                })}
              </div>
            </div>
          </div>
        </>
      ) || (
        <Loading/>
      )}
    </div>
  );
};