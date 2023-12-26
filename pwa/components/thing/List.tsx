import { type NextPage } from "next";
import Head from "next/head";
import { useRouter } from "next/router";
import { useMutation } from "react-query";
import FilterListOutlinedIcon from "@mui/icons-material/FilterListOutlined";
import { MenuItem, Select } from "@mui/material";

import { Item } from "@/components/thing/Item";
import { Filters } from "@/components/thing/Filters";
import { Pagination } from "@/components/common/Pagination";
import { type Thing } from "@/types/Thing";
import { type PagedCollection } from "@/types/collection";
import { type FiltersProps, buildUriFromFilters } from "@/utils/thing";
import { type FetchError, type FetchResponse } from "@/utils/dataAccess";
import { useMercure } from "@/utils/mercure";
import { useTranslation } from 'next-i18next';

interface Props {
  data: PagedCollection<Thing> | null;
  hubURL: string | null;
  filters: FiltersProps;
  page: number;
}

const getPagePath = (page: number): string => `/things?page=${page}`;

export const List: NextPage<Props> = ({ data, hubURL, filters, page }) => {
  const collection = useMercure(data, hubURL);
  const router = useRouter();
  const { t, i18n } = useTranslation('thing');

  const filtersMutation = useMutation<
    FetchResponse<PagedCollection<Thing>> | undefined,
    Error | FetchError,
    FiltersProps
    // @ts-ignore
  >(async (filters) => {
    router.push(buildUriFromFilters("/things", filters));
  });

  return (
    <div className="container mx-auto max-w-7xl items-center justify-between p-6 lg:px-8">
      <Head>
        <title>{t('pageTitleThings')}</title>
      </Head>
      <div className="flex">
        <aside className="float-left w-[180px] mr-6" aria-label="Filters">
          <div className="font-semibold pb-2 border-b border-black text-lg mb-4">
            <FilterListOutlinedIcon className="w-6 h-6 mr-1"/>
            Filters
          </div>
          {/* @ts-ignore */}
          <Filters mutation={filtersMutation} filters={filters}/>
        </aside>
        <div className="float-right w-[1010px] justify-center">
          {!!collection && !!collection["hydra:member"] && (
            <>
              <div className="w-full flex px-8 pb-4 text-lg">
                <div className="float-left flex w-[400px]">
                  <span className="mr-2">Sort by:</span>
                  <Select
                    data-testid="sort"
                    variant="standard"
                    value={filters.order?.name ?? ""}
                    displayEmpty
                    onChange={(event) => {
                      filtersMutation.mutate({ ...filters, order: event.target.value ? { name: event.target.value } : undefined });
                    }}
                    >
                    <MenuItem value="">Relevance</MenuItem>
                    <MenuItem value="asc">Name ASC</MenuItem>
                    <MenuItem value="desc">Name DESC</MenuItem>
                  </Select>
                </div>
                <span data-testid="nb-things" className="float-right mt-1">{collection["hydra:totalItems"]} {t('thingsfound')}</span>
              </div>
              <div className="grid grid-cols-5 gap-4">
                {collection["hydra:member"].length !== 0 && collection["hydra:member"].map((thing) => (
                  <Item key={thing["@id"]} thing={thing}/>
                ))}
              </div>
              <Pagination collection={collection} getPagePath={getPagePath} currentPage={page}/>
            </>
          ) || (
            <p className="w-full flex px-8 pb-4 text-lg">{t('nothingsfound' as const) }</p>
          )}
        </div>
      </div>
    </div>
  );
};