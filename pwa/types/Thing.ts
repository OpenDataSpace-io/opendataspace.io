import { type Item } from "@/types/item";
import { type Thumbnails } from "@/types/Thumbnails";

export class Thing implements Item {
  public "@id"?: string;

  constructor(
    //public book: string,
    public name: string,
    //public condition: string,
    //public reviews: string,
    //public author?: string,
    //public rating?: number,
    _id?: string,
    public id?: string,
    //public slug?: string,
    //public images?: Thumbnails,
    //public description?: string,
    public dateCreated?: string,
    public dateModified?: string,
    public properties?: any,
  ) {
    this["@id"] = _id;
  }
}
