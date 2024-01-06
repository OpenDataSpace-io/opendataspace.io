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
import { fetch, type FetchError, type FetchResponse } from "@/utils/dataAccess";
import { type PagedCollection } from "@/types/collection";
import { Loading } from "@/components/common/Loading";
//import { ExportMenu } from "@/components/common/Export";
import Menu from '@mui/material/Menu';
import MenuItem from '@mui/material/MenuItem';
import MoreVertIcon from '@mui/icons-material/MoreVert';
import Grid from '@mui/material/Grid';
import { useTranslation } from 'next-i18next';
import FormattedDate from '@/utils/formattedDate';

interface Props {
  data: Thing;
  hubURL: string | null;
  page: number;
}

export const Show: NextPage<Props> = ({ data, hubURL, page }) => {

  const item = useMercure(data, hubURL);
  const { t, i18n} = useTranslation('common');

  const [anchorEl, setAnchorEl] = React.useState<null | HTMLElement>(null);
  const open = Boolean(anchorEl);
  const handleClick = (event: React.MouseEvent<HTMLButtonElement>) => {
    setAnchorEl(event.currentTarget);
  };
  const handleClose = () => {
    setAnchorEl(null);
  };

  const [showMetadata, setShowMetadata] = useState(false); // Add state to control metadata visibility

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
      </div>
      {!!item && (
        <>
          <div className="flex">
            <div className="min-w-[270px] max-w-[300px] w-full mr-10 text-center">
              {!!item["image"] && (
                <Image alt={item["name"]} width={300} height={300} src={item["image"]} priority={true} data-testid="thing-cover" />
              ) || (
                  <span className="h-40 text-slate-300">{t('things.show.noimage')}</span>
                )}
            </div>
            <div className="w-full">
            <Grid container spacing={2}>
              <Grid item xs={8}>
                <h1 className="font-bold text-2xl text-gray-700">{item["name"]}</h1>
              </Grid>
              <Grid item xs={4}>
              <div className="lg:flex lg:flex-1 lg:justify-end lg:gap-x-12">
                <Button
                  id="basic-button"
                  aria-controls={open ? 'basic-menu' : undefined}
                  aria-haspopup="true"
                  aria-expanded={open ? 'true' : undefined}
                  onClick={handleClick}
                >
                 < MoreVertIcon />
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
                    <Link href={item['@id'] + "/edit"}>{t('things.show.edit')}</Link>
                  </MenuItem>
                  <MenuItem onClick={handleClose}>
                    <a href={`${item['@id']}.json`}>{t('things.show.exportjson')}</a>
                  </MenuItem>
                  <MenuItem onClick={handleClose}>
                    <a href={`${item['@id']}.jsonld`}>{t('things.show.exportjsonld')}</a>
                  </MenuItem>
                </Menu>
              </div>
              </Grid>
            </Grid>
              
              <span className="flex">
                  <span>{t('things.show.dateCreated')}: <FormattedDate dateString={item["dateCreated"] ?? ""} /></span>
                  {!!item["dateModified"] && (
                    <span className="ml-1"> | {t('things.show.dateModified')} <FormattedDate dateString={item["dateModified"] ?? ""} /></span>
                  )}
                </span>
              <p className="text-justify leading-7 my-8" data-testid="thing-description">
                {item["description"] && (
                  item["description"]
                ) || (
                    <span className="text-slate-300">{t('things.show.nodescription')}</span>
                )}
              </p>
              <div className="px-4 sm:px-0">
                <h3 className="text-base font-semibold leading-7 text-gray-900">Metadaten</h3>
                <p className="mt-1 max-w-2xl text-sm leading-6 text-gray-500"></p>
              </div>
              <div className="mt-6 border-t border-gray-10" data-testid="thing-metadata">
              {showMetadata && (<Button onClick={() => setShowMetadata(false)}>{t('things.show.closemetadata')}</Button>)}
                {showMetadata && ( // Render metadata only if showMetadata is true
                  <dl className="divide-y divide-gray-100">
                    {Object.entries(item).map(([key, value]) => {
                      if (Array.isArray(value)) {
                        return (
                          <span key={key}>
                            {value.map((item, index) => (
                              <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0" key={index}>
                                <dt className="text-sm font-medium leading-6 text-gray-900">{key}[{index}]</dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{JSON.stringify(item)}</dd>
                              </div>
                            ))}
                          </span>
                        );
                      } else if (typeof value === 'object' && value !== null) {
                        return (
                          <span key={key}>
                            {Object.entries(value).map(([subKey, subValue]) => (
                              <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0" key={subKey}>
                                <dt className="text-sm font-medium leading-6 text-gray-900">{key}.{subKey}</dt>
                                <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{JSON.stringify(subValue)}</dd>
                              </div>
                            ))}
                          </span>
                        );
                      } else {
                        return (
                          <div className="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0" key={key}>
                            <dt className="text-sm font-medium leading-6 text-gray-900">{key}</dt>
                            <dd className="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{value}</dd>
                          </div>
                        );
                      }
                    })}
                  </dl>
                )}
                {!showMetadata && ( // Render a button to show metadata if showMetadata is false
                  <Button onClick={() => setShowMetadata(true)}>{t('things.show.showmetadata')}</Button>
                )}
              </div>
            </div>
          </div>
        </>
      ) || (
          <Loading />
        )}
    </div>
  );
};