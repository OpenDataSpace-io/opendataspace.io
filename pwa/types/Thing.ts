import { type Item } from "@/types/item";
//import { type Thumbnails } from "@/types/Thumbnails";

export class Thing implements Item {
  public "@id"?: string;

  constructor(
    public name: string,
    _id?: string,
    public id?: string,
    //public images?: Thumbnails,
    public image?: string,
    public description?: string,
    public dateCreated?: string,
    public dateModified?: string,
    public properties?: any,
    
  ) {
    this["@id"] = _id;
  }
}