import type {
  GetServerSideProps,
  InferGetServerSidePropsType,
} from 'next'

import { useTranslation } from 'next-i18next'
import { serverSideTranslations } from 'next-i18next/serverSideTranslations'

import { List } from "@/components/thing/List";
import { type Thing } from "@/types/Thing";
import { type PagedCollection } from "@/types/collection";
import { type FetchResponse, fetch } from "@/utils/dataAccess";
import { type FiltersProps, buildUriFromFilters } from "@/utils/thing";

export const getServerSideProps: GetServerSideProps<{
  data: PagedCollection<Thing> | null,
  hubURL: string | null,
  filters: FiltersProps,
}> = async ({ query, locale }) => {
  const page = Number(query.page ?? 1);
  const filters: FiltersProps = {};
  if (query.page) {
    // @ts-ignore
    filters.page = query.page;
  }
  if (query.dateCreated) {
    // @ts-ignore
    filters.dateCreated = query.dateCreated;
  }
  if (query.dateModified) {
    // @ts-ignore
    filters.dateModified = query.dateModified;
  }
  if (query.name) {
    // @ts-ignore
    filters.name = query.name;
  }
  if (query["order[name]"]) {
    // @ts-ignore
    filters.order = { name: query["order[name]"] };
  }

  try {
    const response: FetchResponse<PagedCollection<Thing>> | undefined = await fetch(buildUriFromFilters("/things", filters));
    if (!response?.data) {
      throw new Error('Unable to retrieve data from /things.');
    }

    return { 
      props: { 
        data: response.data, 
        hubURL: response.hubURL, 
        filters, 
        page,
        ...(await serverSideTranslations(locale ?? 'en', [
          'common',
          'thing'
        ])),
      } 
    };
  } catch (error) {
    console.error(error);
  }

  return { props: { 
    data: null, 
    hubURL: null, 
    filters, 
    page,
    ...(await serverSideTranslations(locale ?? 'en', [
      'common',
      'thing'
    ])),
   }
  };
};

export default List;