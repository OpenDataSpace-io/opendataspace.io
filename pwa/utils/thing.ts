import slugify from "slugify";
import { useQuery } from "react-query";

import { isItem } from "@/types/item";
import { type Thing } from "@/types/Thing";

interface OrderFilter {
  name: string;
}

export interface FiltersProps {
  name?: string | undefined;
  dateCreated?: string | undefined;
  dateModified?: string | undefined;
  order?: OrderFilter | undefined;
  page?: number | undefined;
}

const filterObject = (object: object) => Object.fromEntries(Object.entries(object).filter(([, value]) => {
  return typeof value === "object" ? Object.keys(value).length > 0 : value?.length > 0;
}));


export const buildUriFromFilters = (uri: string, filters: FiltersProps): string => {
  // remove empty filters
  filters = filterObject(filters);

  const params = new URLSearchParams();
  Object.keys(filters).forEach((filter: string) => {
    // @ts-ignore
    const value = filters[filter];
    if (typeof value === "string" || typeof value === "number") {
      params.append(filter, value.toString());
    } else if (Array.isArray(value)) {
      value.forEach((v: string) => {
        params.append(`${filter}[]`, v);
      });
    } else if (typeof value === "object") {
      // @ts-ignore
      Object.entries(value).forEach(([k, v]) => params.append(`${filter}[${k}]`, v));
    }
  });

  return `${uri}${params.size === 0 ? "" : `?${params.toString()}`}`;
};