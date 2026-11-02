import { Injectable } from "@nestjs/common";

@Injectable()
export class SubscriptionRegistry {
  private rooms = new Map<string, number>();

  add(room: string) {
    console.log("room added : ", room)
    this.rooms.set(room, (this.rooms.get(room) || 0) + 1);
  }

  remove(room: string) {
    const count = this.rooms.get(room) || 0;

    if (count <= 1) {
      this.rooms.delete(room);
    } else {
      this.rooms.set(room, count - 1);
    }
  }

  get(room: string): number {
    return this.rooms.get(room) || 0;
  }

  has(room: string): boolean {
    return this.rooms.has(room);
  }

  set(room: string, value: number) {
    this.rooms.set(room, value);
  }

  delete(room: string) {
    this.rooms.delete(room);
  }

  isActive(room: string): boolean {
    return this.get(room) > 0;
  }

  getAll(): string[] {
    return [...this.rooms.keys()];
  }
}