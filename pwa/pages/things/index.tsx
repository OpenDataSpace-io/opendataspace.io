import { type GetServerSideProps } from "next";

import { List } from "@/components/thing/List";
import { type Thing } from "@/types/Thing";
import { type PagedCollection } from "@/types/collection";
import { type FetchResponse, fetch } from "@/utils/dataAccess";
import { type FiltersProps, buildUriFromFilters } from "@/utils/thing";

export const getServerSideProps: GetServerSideProps<{
  data: PagedCollection<Thing> | null,
  hubURL: string | null,
  filters: FiltersProps,
}> = async ({ query }) => {
  const page = Number(query.page ?? 1);
  const filters: FiltersProps = {};
  if (query.page) {
    // @ts-ignore
    filters.page = query.page;
  }
  //if (query.title) {
    // @ts-ignore
    //filters.title = query.title;
  //}

  try {
    const response: FetchResponse<PagedCollection<Thing>> | undefined = await fetch(buildUriFromFilters("/things", filters));
    if (!response?.data) {
      throw new Error('Unable to retrieve data from /things.');
    }

    return { props: { data: response.data, hubURL: response.hubURL, filters, page } };
  } catch (error) {
    console.error(error);
  }

  return { props: { data: null, hubURL: null, filters, page } };
};

export default List;
